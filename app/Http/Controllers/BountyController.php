<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Thujohn\Twitter\Facades\Twitter;
use App\TwitterBountyUser;

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

	    	$saved = $user->save();

	    	if ($saved) {
	    		return view('twitter/twitter-done');
	    	} else {
	    		return view('twitter/twitter');
	    	}
	    }
    }

}