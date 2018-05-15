<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Thujohn\Twitter\Facades\Twitter;
use App\TwitterBountyUser;
use App\Exports\TwitterExport;

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

    public function twitterSubmit(Request $request)
    {
    	try {
    		$result = Twitter::getUsers(["screen_name" => $request->input("twitter_username")]);
    	} catch (\Exception $e) {
    		//return redirect()->back()->with('error', ["Could not find any user with the name " . $request->input("twitter_username")]);
    		return \Redirect::back()->withErrors("Could not find any user with the name " . $request->input("twitter_username"));
    	}
    	

    	$user = TwitterBountyUser::where("twitter_id", $result->id_str)->orWhere("eth_address", $request->input("eth_address"))->first();
    	if ($user) {
    		return view('twitter/twitter-done')->with('test','test');
    	} else {
	    	$user = new TwitterBountyUser;
	    	$user->twitter_username = $request->input("twitter_username");
	    	$user->eth_address = $request->input("eth_address");
	    	$user->twitter_id = $result->id_str;

            $json = file_get_contents("https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=" . $request->input("twitter_username"),true);
            $obj = json_decode($json);
            $user->twitter_followers_count = $obj[0]->followers_count;

	    	$saved = $user->save();

	    	if ($saved) {
	    		return view('twitter/twitter-done');
	    	} else {
	    		return view('twitter/twitter');
	    	}
	    }
    }

    public function twitterExport() {

        $users = TwitterBountyUser::where('id', '>', 0);
        $users->update(['is_following' => 0, 'has_retweeted' => 0]);

        $all = array();
        $cursor = "-1";

        do {

            $result = \Twitter::getFollowersIds(["screen_name" => "opetfoundation", "cursor" => $cursor]);
            $cursor = $result->next_cursor_str;

            $followers = $result->ids;

            $all = array_merge($all,$followers);

        } while ($cursor != 0);

        $followed = TwitterBountyUser::whereIn('twitter_id', $all);
        $followed->update(['is_following' => 1]);


        $all = array();
        $cursor = "-1";

        do {

            $result = \Twitter::getRters(["id" => "996288621203329024", "count" => 100, "cursor" => $cursor]);
            $cursor = $result->next_cursor_str;

            $retweeters = $result->ids;

            $all = array_merge($all,$retweeters);

        } while ($cursor != 0);

        $retweeted = TwitterBountyUser::whereIn('twitter_id', $all);
        $retweeted->update(['has_retweeted' => 1]);



        return (new TwitterExport())->download('twitter_bounty_results.xlsx');
    }

}
