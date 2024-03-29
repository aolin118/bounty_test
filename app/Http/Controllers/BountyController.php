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
        if(Session::has('email')) {
            return redirect(route('bounty-submit-get'));
        }
        return view('landing');
    }

    public function end() 
    {
        return view('complete');
    }

    public function bountyReferral($referral)
    {
        if(Session::has('email')) {
            return redirect(route('bounty-submit-get'));
        }

        $referrer = BountyUser::where('unique_link',$referral)->first();

        if ($referrer) {
            return view('landing')->with('referrer',$referral);
        } else {
            return redirect('/');
        }
    }

    public function main()
    {   
        if(!Session::has('email')) {
            return redirect('/');
        }

        $user = BountyUser::where("email",Session::get('email'))->first();

        if ($user) {

            $awarded = 0;

            if ($user->telegram_completed == 1) $awarded += 4;
            if ($user->twitter_completed == 1) $awarded += 2;
            if ($user->youtube_completed == 1) $awarded += 2;
            if ($user->reddit_completed == 1) $awarded += 2;
            if ($user->medium_completed == 1) $awarded += 2;
            if ($user->facebook_completed == 1) $awarded += 2;
            if ($user->instagram_completed == 1) $awarded += 2;
            if ($user->linkedin_completed == 1) $awarded += 2;

            $referral = BountyUser::where("referrer",$user->id)
                                    ->where(function($q) {
                                          $q->where('telegram_completed', 1)
                                            ->orWhere('twitter_completed', 1)
                                            ->orWhere('youtube_completed', 1)
                                            ->orWhere('reddit_completed', 1)
                                            ->orWhere('medium_completed', 1)
                                            ->orWhere('facebook_completed', 1)
                                            ->orWhere('instagram_completed', 1)
                                            ->orWhere('linkedin_completed', 1);
                                      })->count();

            $awarded += ($referral * 2);

            $authURL = $this->getAuthURLs($user);
            return view('instructions')->with('user',$user)->with('authURL', $authURL)->with('awarded',$awarded);

        } else {
            return redirect('/');
        }
    }

    public function addressSubmit(Request $request)
    {
        if (!$request->has("email")) {
            return redirect('/');
        }

        $email = $request->input("email");

        $user = BountyUser::where("email",$email)->first();

        if ($user) {
            Session::put('email', $email);
            return redirect(route('bounty-submit-get'));
        } else {
            if ($request->has("referrer")) {
                $referrer = BountyUser::where("unique_link",$request->input('referrer'))->first();
                $new = new BountyUser;
                $new->email = $email;
                $new->referrer = $referrer->id;
                $new->unique_link = md5(uniqid($email, true));
                $saved = $new->save();

                if ($saved) {
                    Session::put('email', $email);
                    return redirect(route('bounty-submit-get'));
                }
            } else {
                $new = new BountyUser;
                $new->email = $email;
                $new->unique_link = md5(uniqid($email, true));
                $saved = $new->save();

                if ($saved) {
                    Session::put('email', $email);
                    return redirect(route('bounty-submit-get'));
                }
            }
            

            
        }
    }

    public function logOut() {
        Session::forget('email');
        return redirect('/');
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
                $client->addScope(Google_Service_YouTube::YOUTUBEPARTNER);
                $client->addScope(Google_Service_YouTube::YOUTUBE);
                $client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
                $client->setAccessType('offline');
                $client->setApprovalPrompt('force');

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

                //$redditClient = new \OAuth2\Client($redditClientID, $redditClientSecret);
                $redditClient = new \OAuth2\Client($redditClientID, $redditClientSecret, \OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);
                $authURL['reddit'] = $redditClient->getAuthenticationUrl($redditAuthorizationEndpoint, $redditRedirectURI, array("scope" => "identity,read,mysubreddits", "state" => uniqid('', true), "duration" => "permanent"));
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
        if(!Session::has('email')) {
            return redirect('/');
        }

        $request_token = [];
        $request_token['oauth_token'] = Session::get('oauth_token');
        $request_token['oauth_token_secret'] = Session::get('oauth_token_secret');

        if ($request->has('oauth_token') && $request_token['oauth_token'] !== $request->input('oauth_token')) {
            return redirect(route('bounty-submit-get'));
        } else {
            $connection = new TwitterOAuth(env("TWITTER_CLIENT_ID", ""), env("TWITTER_CLIENT_SECRET", ""), $request_token['oauth_token'], $request_token['oauth_token_secret']);
            $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $request->input('oauth_verifier')]);

            $user = BountyUser::where("email", Session::get('email'))->first();

            if ($user) {
                $twitterToken = new TwitterToken;
                $twitterToken->bounty_user_id = $user->id;
                $twitterToken->access_token = json_encode($access_token);
                $twitterToken->save();
            }

            return redirect(route('bounty-submit-get'));

        }
    }

    public function youtubeCallback(Request $request) {
        if(!Session::has('email')) {
            return redirect('/');
        }

        $client = new Google_Client();
        // Set to name/location of your client_secrets.json file.
        $client->setClientId(env("GOOGLE_CLIENT_ID", ""));
        $client->setClientSecret(env("GOOGLE_CLIENT_SECRET", ""));
        // Set to valid redirect URI for your project.
        $client->setRedirectUri(route('bounty-youtube-callback'));

        $client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
        $client->addScope(Google_Service_YouTube::YOUTUBEPARTNER);
        $client->addScope(Google_Service_YouTube::YOUTUBE);
        $client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        $accessToken = $client->authenticate($request->input("code"));
        $client->setAccessToken($accessToken);

        $user = BountyUser::where("email", Session::get('email'))->first();
        if ($user) {
            $youtubeToken = new YoutubeToken;
            $youtubeToken->bounty_user_id = $user->id;
            $youtubeToken->access_token = json_encode($accessToken);
            $youtubeToken->save();

            if ($client->isAccessTokenExpired()) {
                $client->refreshToken($client->getRefreshToken());
                $youtubeToken->access_token = json_encode($client->getAccessToken());
                $youtubeToken->save();
            }
        }

        return redirect(route('bounty-submit-get'));
    }

    public function redditCallback(Request $request) {
        if(!Session::has('email')) {
            return redirect('/');
        }

        $redditTokenEndpoint = 'https://ssl.reddit.com/api/v1/access_token';

        //$redditClient = new \OAuth2\Client($redditClientID, $redditClientSecret);
        $redditClient = new \OAuth2\Client(env("REDDIT_CLIENT_ID", ""), env("REDDIT_CLIENT_SECRET", ""), \OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);

        $params = array("code" => $request->input('code'), "redirect_uri" => route('bounty-reddit-callback'));
        $response = $redditClient->getAccessToken($redditTokenEndpoint, "authorization_code", $params);

        $accessTokenResult = $response["result"];
        $redditClient->setAccessToken($accessTokenResult["access_token"]);
        $redditClient->setAccessTokenType(\OAuth2\Client::ACCESS_TOKEN_BEARER);

        $user = BountyUser::where("email", Session::get('email'))->first();
        if ($user) {

            $redditToken = new RedditToken;
            $redditToken->bounty_user_id = $user->id;
            $redditToken->access_token = json_encode($accessTokenResult);
            $redditToken->save();

        }

        return redirect(route('bounty-submit-get'));
    }

    public function mediumCallback(Request $request) {
        if(!Session::has('email')) {
            return redirect('/');
        }

        $mediumTokenEndpoint = 'https://api.medium.com/v1/tokens';

        $mediumClient = new \OAuth2\Client(env("MEDIUM_CLIENT_ID", ""), env("MEDIUM_CLIENT_SECRET", ""));

        $params = array("code" => $request->input('code'), "redirect_uri" => route('bounty-medium-callback'));
        $response = $mediumClient->getAccessToken($mediumTokenEndpoint, "authorization_code", $params);

        $accessTokenResult = $response["result"];
        $mediumClient->setAccessToken($accessTokenResult["access_token"]);
        $mediumClient->setAccessTokenType(\OAuth2\Client::ACCESS_TOKEN_BEARER);

        $user = BountyUser::where("email", Session::get('email'))->first();
        if ($user) {
            $mediumToken = new MediumToken;
            $mediumToken->bounty_user_id = $user->id;
            $mediumToken->access_token = json_encode($accessTokenResult);
            $mediumToken->save();
        }

        return redirect(route('bounty-submit-get'));
    }

    public function telegramVerify() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();

            if ($user->telegram()->exists()) {
                $groups = ["@bcoinsg_EN", "@bcoinsg_CN"];
                $channel = "@bcoinsg";

                $result = \Telegram::getChatMember(['chat_id' => $channel, 'user_id' => $user->telegram->telegram_id]);
                $chatMemberChannel = ($result->getDecodedBody())['result'];

                $result = \Telegram::getChatMember(['chat_id' => $groups[0], 'user_id' => $user->telegram->telegram_id]);
                $chatMemberEN = ($result->getDecodedBody())['result'];

                $result = \Telegram::getChatMember(['chat_id' => $groups[1], 'user_id' => $user->telegram->telegram_id]);
                $chatMemberCN = ($result->getDecodedBody())['result'];

                if ($chatMemberChannel['status'] == "member" && ($chatMemberEN['status'] == "member" || $chatMemberCN['status'] == "member")) {
                    $user->telegram_completed = 1;
                    $user->save();
                    echo "true";
                } else {
                    if ($chatMemberEN['status'] != "member" || $chatMemberCN['status'] != "member") {
                        echo "You have not joined our Telegram Group.";
                    } else {
                        echo "You have not joined our Telegram Channel.";
                    }
                }
            } else {
                return redirect('/');
            }
        }
        
    }

    public function twitterVerify() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();

            if ($user->twitter()->exists()) {

                $follow = false;
                $like = false;
                $retweet = false;

                $access_token = json_decode($user->twitter->access_token,true);

                $twitterPageID = "969390070372290560";
                $twitterTweetID = "1064428104771350529";

                $connection = new TwitterOAuth(env("TWITTER_CLIENT_ID", ""), env("TWITTER_CLIENT_SECRET", ""), $access_token['oauth_token'], $access_token['oauth_token_secret']);

                $cursor = "-1";
                while ($cursor != "0") {
                    $results = json_decode(json_encode($connection->get("friends/ids", ["user_id" => $access_token['user_id'], "count" => 5000, "cursor" => $cursor])),true);
                    $cursor = $results["next_cursor_str"];

                    if (in_array($twitterPageID, $results["ids"])) {
                        $follow = true;
                        break;
                    }
                }



                $results = json_decode(json_encode($connection->get("statuses/user_timeline", ["user_id" => $access_token['user_id'], "count" => 200, "cursor" => $cursor, "since_id" => $twitterTweetID, "exclude_replies" => true, "include_rts" => true])),true);

                foreach($results as $tweet) {
                    if (isset($tweet['retweeted_status'])) {
                        if ($tweet['retweeted_status']['id_str'] == $twitterTweetID) {
                            $retweet = true;

                            if ($tweet['favorited'] == true) {
                                $like = true;
                            }
                            break;
                        }
                    }
                }

                if ($follow && $like && $retweet) {
                    $user->twitter_completed = 1;
                    $user->save();

                    echo "true";
                } else {
                    if (!$follow) {
                        echo "You have not followed our Twitter Page.";
                    } else {
                        echo "You have not liked and retweeted our tweet.";
                    }
                }
            } else {
                return redirect('/');
            }
        }
    }

    public function youtubeVerify() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();

            if ($user->youtube()->exists()) {
                $subscribed = false;
                $like = false;

                $access_token = json_decode($user->youtube->access_token,true);

                $client = new Google_Client();
                // Set to name/location of your client_secrets.json file.
                $client->setClientId(env("GOOGLE_CLIENT_ID", ""));
                $client->setClientSecret(env("GOOGLE_CLIENT_SECRET", ""));
                // Set to valid redirect URI for your project.
                $client->setRedirectUri(route('bounty-youtube-callback'));

                $client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
                $client->addScope(Google_Service_YouTube::YOUTUBEPARTNER);
                $client->addScope(Google_Service_YouTube::YOUTUBE);
                $client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
                $client->setAccessType('offline');
                $client->setApprovalPrompt('force');

                $client->setAccessToken($access_token);

                //dd($access_token);

                if ($client->isAccessTokenExpired()) {
                    $client->refreshToken($client->getRefreshToken());

                    $youtubeToken = YoutubeToken::find($user->youtube->id);
                    $youtubeToken->access_token = json_encode($client->getAccessToken());
                    $youtubeToken->save();
                }

                $service = new Google_Service_YouTube($client);

                $response = $service->subscriptions->listSubscriptions(
                    "snippet,contentDetails",
                    array_filter(["mine" => true, "forChannelId" => "UCfD4r29eHpn_XTtqrKh3Xig"])
                );

                if (count($response['items']) == 1) {
                    $subscribed = true;
                }

                $response = $service->videos->getRating(
                    "TtAUV7MUW5k",
                    array_filter(['onBehalfOfContentOwner' => ''])
                );

                if ($response['items'][0]['rating'] == "like") {
                    $like = true;
                }

                if ($subscribed && $like) {
                    $user->youtube_completed = 1;
                    $user->save();

                    echo "true";
                } else {
                    if (!$subscribed) {
                        echo "You have not subscribed to our YouTube Channel.";
                    } else {
                        echo "You have not liked our video.";
                    }
                }

            } else {
                return redirect('/');
            }
        }
    }

    public function redditVerify() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();

            if ($user->reddit()->exists()) {
                $subscribed = false;
                $upvote = false;

                $access_token = json_decode($user->reddit->access_token,true);

                $client = new \OAuth2\Client(env("REDDIT_CLIENT_ID", ""), env("REDDIT_CLIENT_SECRET", ""), \OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);
                $client->setAccessToken($access_token["access_token"]);
                $client->setAccessTokenType(\OAuth2\Client::ACCESS_TOKEN_BEARER);
                $client->setCurlOption(CURLOPT_USERAGENT,"BCoinClient/0.1 by Talenta");

                // Subscribe
                $response = $client->fetch("https://oauth.reddit.com/api/info.json", ["id" => "t5_mx0d3"], "GET", [], 1);

                if ($response['result']['data']['children'][0]['data']['user_is_subscriber'] == true) {
                    $subscribed = true;
                }

                // Upvote
                $response = $client->fetch("https://oauth.reddit.com/api/info.json", ["id" => "t3_9ypagi"], "GET", [], 1);

                if ($response['result']['data']['children'][0]['data']['likes'] == true) {
                    $upvote = true;
                }

                if ($subscribed && $upvote) {
                    $user->reddit_completed = 1;
                    $user->save();

                    echo "true";
                } else {
                    if (!$subscribed) {
                        echo "You have not subscribed to our Subreddit.";
                    } else {
                        echo "You have not upvoted our post.";
                    }
                }

            } else {
                return redirect('/');
            }
        }
    }

    public function mediumVerify() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();

            if ($user->medium()->exists()) {
                $followed = false;

                $access_token = json_decode($user->medium->access_token,true);

                $mediumClient = new \OAuth2\Client(env("MEDIUM_CLIENT_ID", ""), env("MEDIUM_CLIENT_SECRET", ""));
                $mediumClient->setAccessToken($access_token["access_token"]);
                $mediumClient->setAccessTokenType(\OAuth2\Client::ACCESS_TOKEN_BEARER);

                $response = $mediumClient->fetch("https://api.medium.com/v1/me");

                $medium_id = $response['result']['data']['id'];

                $response = $mediumClient->fetch("https://api.medium.com/v1/users/" . $medium_id . "/publications");

                foreach($response['result']['data'] as $publication) {
                    if ($publication['url'] == "https://medium.com/bcoinsg") {
                        $followed = true;
                        break;
                    }
                }

                if ($followed) {
                    $user->medium_completed = 1;
                    $user->save();

                    echo "true";
                } else {
                    echo "You have not followed our Medium page.";
                }

            } else {
                return redirect('/');
            }
        }
    }

    public function facebookVerify() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();
            $user->facebook_completed = 1;
            $user->save();

            return redirect(route('bounty-submit-get'));
        }
    }

    public function instagramVerify() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();
            $user->instagram_completed = 1;
            $user->save();

            return redirect(route('bounty-submit-get'));
        }
    }

    public function linkedInVerify() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();
            $user->linkedin_completed = 1;
            $user->save();

            return redirect(route('bounty-submit-get'));
        }
    }

    public function interestChange() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();

            $currentInterest = $user->card_interest;

            if ($currentInterest == 0) {
                $user->card_interest = 1;
                $user->save();
            } else {
                $user->card_interest = 0;
                $user->save();
            }

            return redirect(route('bounty-submit-get'));
        }
    }

    public function newsletterChange() {
        if(!Session::has('email')) {
            return redirect('/');
        } else {
            $user = BountyUser::where("email", Session::get('email'))->first();

            $currentNewsletter = $user->receive_newsletter;

            if ($currentNewsletter == 0) {
                $user->receive_newsletter = 1;
                $user->save();
            } else {
                $user->receive_newsletter = 0;
                $user->save();
            }

            return redirect(route('bounty-submit-get'));
        }
    }

    public function airdropExport() {

        $airdropAll = TelegramUser::select("telegram_users.id", "telegram_users.email", "telegram_users.telegram_id", "telegram_users.referrer", DB::raw('IFNULL(t2.count,0) as refer_count'), "telegram_users.created_at", "telegram_users.updated_at")
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
