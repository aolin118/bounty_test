<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Thujohn\Twitter\Facades\Twitter;
use App\TwitterBountyUser;
use App\Retweeter;
use App\Exports\TwitterExport;
use DB;
use Redirect;
use App\Classes\XLSXWriter;

class BountyController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function twitter()
    {
        return view('twitter/twitter');
    }

    public function twitterEnd()
    {
        return view('twitter/twitter-end');
    }

    public function twitterReferral($id)
    {
        $referrer = TwitterBountyUser::where('twitter_username',$id)->first();

        if ($referrer) {
            return view('twitter/twitter')->with('referrer',$referrer->twitter_username);
        } else {
            return redirect('twitter');
        }
    }

    public function twitterSubmit(Request $request)
    {
    	try {

            $username = $request->input("twitter_username");

            if (substr($username,0,1) == "@") {
                $username = substr($username,1,strlen($username));
            }

    		$result = Twitter::getUsers(["screen_name" => $username]);
    	} catch (\Exception $e) {
    		//return redirect()->back()->with('error', ["Could not find any user with the name " . $request->input("twitter_username")]);
    		return \Redirect::back()->withErrors("Could not find any user with the name " . $username);
    	}
    	

    	$user = TwitterBountyUser::where("twitter_id", $result->id_str)->orWhere("eth_address", $request->input("eth_address"))->first();
    	if ($user) {
    		return view('twitter/twitter-done')->with('link', route('twitter-get') . "/" . $user->twitter_username);
    	} else {
	    	$user = new TwitterBountyUser;
	    	$user->twitter_username = $username;
	    	$user->eth_address = $request->input("eth_address");
	    	$user->twitter_id = $result->id_str;

            $json = file_get_contents("https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=" . $username,true);
            $obj = json_decode($json);
            $user->twitter_followers_count = $obj[0]->followers_count;

            $user->referrer = $request->input("referrer");

	    	$saved = $user->save();

	    	if ($saved) {
	    		return view('twitter/twitter-done')->with('link', route('twitter-get') . "/" . $user->twitter_username);
	    	} else {
	    		return view('twitter/twitter');
	    	}
	    }
    }

    public function twitterExport() {

        $users = TwitterBountyUser::where('id', '>', 0);
        $users->update(['is_following' => 0]);

        $all = array();
        $cursor = "-1";

        do {

            $result = \Twitter::getFollowersIds(["screen_name" => "opetfoundation", "count" => 5000, "cursor" => $cursor]);
            $cursor = $result->next_cursor_str;

            $followers = $result->ids;

            $all = array_merge($all,$followers);

        } while ($cursor != 0);

        $followed = TwitterBountyUser::whereIn('twitter_id', $all);
        $followed->update(['is_following' => 1]);

        $all = array();

        $retweeters = Retweeter::all();
        foreach ($retweeters as $retweeter) {
            array_push($all,$retweeter->twitter_id);
        }

        $retweeted = TwitterBountyUser::whereIn('twitter_id', $all);
        $retweeted->update(['has_retweeted' => 1]);

        $bountyAll = TwitterBountyUser::select("twitter_bounty_users.id", "twitter_bounty_users.twitter_username", "twitter_bounty_users.twitter_id", "twitter_bounty_users.twitter_followers_count", "twitter_bounty_users.referrer", "twitter_bounty_users.eth_address", "twitter_bounty_users.is_following", "twitter_bounty_users.has_retweeted", DB::raw('t2.count as refer_count'), "twitter_bounty_users.created_at", "twitter_bounty_users.updated_at")
                                    ->leftJoin(DB::raw("(SELECT referrer, count(*) as count FROM `twitter_bounty_users` t1 WHERE is_following = 1 AND has_retweeted = 1 GROUP BY referrer) as t2"), 'twitter_bounty_users.twitter_username', '=', 't2.referrer')->get()->toArray();


        $header = array(
          '#'=>'integer',
          'Twitter Username'=>'string',
          'Twitter ID'=>'string',
          'Followers Count'=>'integer',
          'Referrer'=>'string',
          'Ethereum Address'=>'string',
          'Is Following'=>'integer',
          'Has Retweeted'=>'integer',
          'Refer Count'=>'integer',
        );

        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header);

        foreach($bountyAll as $row) $writer->writeSheetRow('Sheet1', $row);

        $writer->writeToFile('twitter_bounty_results.xlsx');

        return response()->download(public_path() . "/export/twitter_bounty_results.xlsx")->deleteFileAfterSend(true);
    }

    public function twitterGetRetweet() {
        $start_id = "996288621203329024";
        $max_id = "-1";

        for($i = 0; $i < 150; $i++) {
            if ($max_id == "-1") {

                $result = \Twitter::getSearch(["q"=> "Õpet uses machine learning and artificial intelligence to make you smarter, faster and better during your journey as a High School student. Watch the video below to learn more!","since_id" => $start_id, "count" => 100]);
            } else {
                $result = \Twitter::getSearch(["q"=> "Õpet uses machine learning and artificial intelligence to make you smarter, faster and better during your journey as a High School student. Watch the video below to learn more!","since_id" => $start_id, "max_id" => $max_id, "count" => 100]);
            }

            $statuses = $result->statuses;
            

            foreach($statuses as $status) {
                if (isset($status->retweeted_status)) {
                    if ($status->retweeted_status->id_str == "996288621203329024") {
                        $exists = Retweeter::where("twitter_id", $status->user->id_str)->count();

                        if ($exists == 0) {
                            $retweeter = new Retweeter;
                            $retweeter->twitter_id = $status->user->id_str;
                            $retweeter->post_id = $status->id_str;
                            $retweeter->save();
                        }
                    }
                }

                if ($status === end($statuses)) {
                    $max_id = $status->id_str;
                }
            }
        }

        echo "done";
    }

}