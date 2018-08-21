<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\BountyUser;
use App\Exports\TwitterExport;
use DB;
use Redirect;
use App\Classes\XLSXWriter;
use Illuminate\Support\Facades\Hash;
use Abraham\TwitterOAuth\TwitterOAuth;
use Google_Client;
use Google_Service_YouTube;
use OAuth2\Client;
use OAuth2\GrantType\IGrantType;
use OAuth2\GrantType\AuthorizationCode;
use App\TelegramUser;
use App\TwitterToken;
use App\YoutubeToken;
use App\RedditToken;
use App\MediumToken;

class BountyController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        if(Session::has('eth_address')) {
            return redirect(route('bounty-submit-get', Session::get('eth_address')));
        }
        return view('landing');
    }

    public function bountyReferral($referral)
    {
        $referrer = BountyUser::where('unique_link',$referral)->first();

        if ($referrer) {
            return view('landing')->with('referrer',$referral);
        } else {
            return redirect('/');
        }
    }

    public function addressSubmit($eth_address)
    {   
        if (!preg_match('/^(0x)?[0-9a-zA-Z]{40}$/', $eth_address)) {
            return redirect('/');
        }

        $user = BountyUser::where("eth_address",$eth_address)->first();

        if ($user) {
            Session::put('eth_address', $eth_address);

            $authURL = $this->getAuthURLs($user);

            return view('instructions')->with('user',$user)->with('authURL', $authURL);
        } else {
            $new = new BountyUser;
            $new->eth_address = $eth_address;
            $new->unique_link = md5(uniqid($eth_address, true));
            $saved = $new->save();

            if ($saved) {
                Session::put('eth_address', $eth_address);
                $authURL = $this->getAuthURLs($new);
                return view('instructions')->with('user',$new)->with('authURL', $authURL);
            }
        }
    }

    public function addressSubmitWithReferral($eth_address, Request $request)
    {
        if (!preg_match('/^(0x)?[0-9a-zA-Z]{40}$/', $eth_address)) {
            return redirect('/');
        }

        $user = BountyUser::where("eth_address",$eth_address)->first();

        if ($user) {
            return redirect(route('bounty-submit-get', $eth_address));
        } else {
            $referrer = BountyUser::where("unique_link",$request->input('referrer'))->first();

            $new = new BountyUser;
            $new->eth_address = $eth_address;
            $new->referrer = $referrer->id;
            $new->unique_link = md5(uniqid($eth_address, true));
            $saved = $new->save();

            if ($saved) {
                return redirect(route('bounty-submit-get', $eth_address));
            }
        }
    }

    function getAuthURLs($user) {
        $authURL = array();

        if ($user->twitter_completed == 0) {
            if (!$user->twitter()->exists()) {
                $twitterClient = new TwitterOAuth(env("TWITTER_CLIENT_ID", ""), env("TWITTER_CLIENT_SECRET", ""));
                //$request_token = $twitterClient->oauth('oauth/request_token', array('oauth_callback' => route('bounty-twitter-callback')));
                $request_token = $twitterClient->oauth('oauth/request_token', array('oauth_callback' => 'https://bounty.bcoinsg.io/twitter-callback'));

                Session::put('oauth_token', $request_token['oauth_token']);
                Session::put('oauth_token_secret', $request_token['oauth_token_secret']);

                $authURL['twitter'] = $twitterClient->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
            }
        }

        if ($user->youtube_completed == 0) {
            if (!$user->youtube()->exists()) {
                $client = new Google_Client();
                // Set to name/location of your client_secrets.json file.
                $client->setClientId(env("GOOGLE_CLIENT_ID", ""));
                $client->setClientSecret(env("GOOGLE_CLIENT_SECRET", ""));
                // Set to valid redirect URI for your project.
                $client->setRedirectUri(route('bounty-youtube-callback'));

                $client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
                $client->setAccessType('offline');

                $authURL['youtube'] = $client->createAuthUrl();
            }
        }

        if ($user->reddit_completed == 0) {
            if (!$user->reddit()->exists()) {
                $redditClientID = env("REDDIT_CLIENT_ID", "");
                $redditClientSecret = env("REDDIT_CLIENT_SECRET", "");

                $redditRedirectURI = route('bounty-reddit-callback');
                $redditAuthorizationEndpoint = 'https://ssl.reddit.com/api/v1/authorize';
                //$redditTokenEndpoint = 'https://ssl.reddit.com/api/v1/access_token';

                $redditClient = new \OAuth2\Client($redditClientID, $redditClientSecret);
                $authURL['reddit'] = $redditClient->getAuthenticationUrl($redditAuthorizationEndpoint, $redditRedirectURI, array("scope" => "identity,mysubreddits", "state" => uniqid('', true)));
            }
        }

        if ($user->medium_completed == 0) {
            if (!$user->medium()->exists()) {
                $mediumClientID = env("MEDIUM_CLIENT_ID", "");
                $mediumClientSecret = env("MEDIUM_CLIENT_SECRET", "");

                $mediumRedirectURI = route('bounty-medium-callback');
                $mediumAuthorizationEndpoint = 'https://medium.com/m/oauth/authorize';
                //$TOKEN_ENDPOINT = 'https://api.medium.com/v1/tokens';

                $mediumClient = new \OAuth2\Client($mediumClientID, $mediumClientSecret);
                $authURL['medium'] = $mediumClient->getAuthenticationUrl($mediumAuthorizationEndpoint, $mediumRedirectURI, array("scope" => "basicProfile,listPublications", "state" => uniqid('', true)));
            }
        }

        return $authURL;
    }

    public function twitterCallback(Request $request) {
        if(!Session::has('eth_address')) {
            return redirect('/');
        }

        $request_token = [];
        $request_token['oauth_token'] = Session::get('oauth_token');
        $request_token['oauth_token_secret'] = Session::get('oauth_token_secret');

        if ($request->has('oauth_token') && $request_token['oauth_token'] !== $request->input('oauth_token')) {
            return redirect(route('bounty-submit-get', Session::get('eth_address')));
        } else {
            $connection = new TwitterOAuth(env("TWITTER_CLIENT_ID", ""), env("TWITTER_CLIENT_SECRET", ""), $request_token['oauth_token'], $request_token['oauth_token_secret']);
            $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $request->input('oauth_verifier')]);

            $user = BountyUser::where("eth_address", Session::get('eth_address'))->first();

            if ($user) {
                $twitterToken = new TwitterToken;
                $twitterToken->bounty_user_id = $user->id;
                $twitterToken->access_token = $access_token['oauth_token'];
                $twitterToken->access_token_secret = $access_token['oauth_token_secret'];
                $twitterToken->save();
            }

            return redirect(route('bounty-submit-get', Session::get('eth_address')));

        }
    }

    public function youtubeCallback(Request $request) {
        if(!Session::has('eth_address')) {
            return redirect('/');
        }

        $client = new Google_Client();
        // Set to name/location of your client_secrets.json file.
        $client->setClientId(env("GOOGLE_CLIENT_ID", ""));
        $client->setClientSecret(env("GOOGLE_CLIENT_SECRET", ""));
        // Set to valid redirect URI for your project.
        $client->setRedirectUri(route('bounty-youtube-callback'));

        $client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
        $client->setAccessType('offline');

        $accessToken = $client->authenticate($request->input("code"));
        $client->setAccessToken($accessToken);

        dd($accessToken);

        $user = BountyUser::where("eth_address", Session::get('eth_address'))->first();
        if ($user) {
            $youtubeToken = new YoutubeToken;
            $youtubeToken->bounty_user_id = $user->id;
            $youtubeToken->access_token = $accessToken;
            $youtubeToken->save();

            if ($client->isAccessTokenExpired()) {
                $client->refreshToken($client->getRefreshToken());
                $youtubeToken->access_token = $client->getAccessToken();
                $youtubeToken->save();
            }
        }

        return redirect(route('bounty-submit-get', Session::get('eth_address')));
    }

    public function redditCallback(Request $request) {
        if(!Session::has('eth_address')) {
            return redirect('/');
        }

        $redditClientID = env("REDDIT_CLIENT_ID", "");
        $redditClientSecret = env("REDDIT_CLIENT_SECRET", "");

        $redditRedirectURI = route('bounty-reddit-callback');
        $redditTokenEndpoint = 'https://ssl.reddit.com/api/v1/access_token';

        $redditClient = new \OAuth2\Client($redditClientID, $redditClientSecret);

        $params = array("code" => $request->input('code'), "redirect_uri" => $redditRedirectURI);
        $response = $redditClient->getAccessToken($redditTokenEndpoint, "authorization_code", $params);

        $accessTokenResult = $response["result"];
        $redditClient->setAccessToken($accessTokenResult["access_token"]);
        $redditClient->setAccessTokenType(\OAuth2\Client::ACCESS_TOKEN_BEARER);

        $user = BountyUser::where("eth_address", Session::get('eth_address'))->first();
        if ($user) {

            $redditToken = new RedditToken;
            $redditToken->bounty_user_id = $user->id;
            $redditToken->access_token = $accessTokenResult["access_token"];
            $redditToken->refresh_token = $accessTokenResult["refresh_token"];
            $redditToken->expiry = Carbon::createFromTimestamp($accessTokenResult["expires_in"])->toDateTimeString();
            $redditToken->save();

        }

        return redirect(route('bounty-submit-get', Session::get('eth_address')));
    }

    public function mediumCallback(Request $request) {
        if(!Session::has('eth_address')) {
            return redirect('/');
        }

        $mediumClientID = env("MEDIUM_CLIENT_ID", "");
        $mediumClientSecret = env("MEDIUM_CLIENT_SECRET", "");

        $mediumRedirectURI = route('bounty-medium-callback');
        $mediumTokenEndpoint = 'https://api.medium.com/v1/tokens';

        $mediumClient = new \OAuth2\Client($mediumClientID, $mediumClientSecret);

        $params = array("code" => $request->input('code'), "redirect_uri" => $mediumRedirectURI);
        $response = $mediumClient->getAccessToken($mediumTokenEndpoint, "authorization_code", $params);

        $accessTokenResult = $response["result"];
        $mediumClient->setAccessToken($accessTokenResult["access_token"]);
        $mediumClient->setAccessTokenType(\OAuth2\Client::ACCESS_TOKEN_BEARER);

        $user = BountyUser::where("eth_address", Session::get('eth_address'))->first();
        if ($user) {
            $mediumToken = new MediumToken;
            $mediumToken->bounty_user_id = $user->id;
            $mediumToken->access_token = $accessTokenResult["access_token"];
            $mediumToken->refresh_token = $accessTokenResult["refresh_token"];
            $mediumToken->expiry = Carbon::createFromTimestamp($accessTokenResult["expires_at"])->toDateTimeString();
            $mediumToken->save();
        }

        return redirect(route('bounty-submit-get', Session::get('eth_address')));
    }

    public function airdropExport() {

        $airdropAll = TelegramUser::select("telegram_users.id", "telegram_users.eth_address", "telegram_users.telegram_id", "telegram_users.referrer", DB::raw('IFNULL(t2.count,0) as refer_count'), "telegram_users.created_at", "telegram_users.updated_at")
                                    ->leftJoin(DB::raw("(SELECT referrer, count(*) as count FROM `telegram_users` t1 WHERE telegram_id IS NOT NULL GROUP BY referrer) as t2"), 'telegram_users.referrer', '=', 't2.referrer')->whereNotNull("telegram_users.telegram_id")->get()->toArray();


        $header = array(
          '#'=>'integer',
          'Ethereum Address'=>'string',
          'Telegram ID'=>'string',
          'Referrer ID'=>'string',
          'Refer Count'=>'integer'
        );

        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header);

        foreach($airdropAll as $row) $writer->writeSheetRow('Sheet1', $row);

        $writer->writeToFile('airdrop_results.xlsx');

        return response()->download(public_path() . "/export/airdrop_results.xlsx")->deleteFileAfterSend(true);
    }

}
