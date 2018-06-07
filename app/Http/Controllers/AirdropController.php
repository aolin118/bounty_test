<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\TelegramUser;
use App\Exports\TwitterExport;
use DB;
use Redirect;
use App\Classes\XLSXWriter;
use Illuminate\Support\Facades\Hash;

class AirdropController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return view('airdrop');
    }

    public function airdropReferral($id)
    {
        $referrer = TelegramUser::where('telegram_id',$id)->first();

        if ($referrer) {
            return view('airdrop')->with('referrer',$referrer->telegram_id);
        } else {
            return redirect('airdrop');
        }
    }

    public function addressSubmit(Request $request)
    {
        $user = TelegramUser::where("eth_address",$request->input("eth_address"))->first();

        if ($user) {
            if (is_null($user->telegram_id)) {
                if (is_null($request->input("complete"))) {
                    return view('instructions')->with('user',$user);
                } else {
                    return view('instructions')->with('user',$user)->withErrors("You did not complete all the steps!");
                }
            } else {
                $basePayout = 1;
                $perReferralPayout = 1;
                $count = TelegramUser::where("referrer", $user->telegram_id)->whereNotNull("telegram_id")->count();
                $tokenCount = ($count * $perReferralPayout) + $basePayout;
                return view('complete')->with('user',$user)->with('tokenCount',$tokenCount);
            }

        } else {
            $new = new TelegramUser;
            $new->eth_address = $request->input("eth_address");
            $new->referrer = $request->input("referrer");
            $new->unique_link = md5(uniqid($request->input("eth_address"), true));
            $saved = $new->save();

            if ($saved) {
                return view('instructions')->with('user',$new);
            }
        }
    }

    public function twitter()
    {
        return view('twitter/twitter');
    }

    public function twitterEnd()
    {
        return view('twitter/twitter-end');
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

    public function reddit() {
        $posts = array();

        $reddit   = new Reddit;
        $links  = $reddit->getLinksBySubreddit('ivyproject','2018-05-25',null);
        usort($links, function($a, $b) {
            //return $a['score'] <=> $b['score'];
            return $b['score'] <=> $a['score'];
        });

        foreach($links as $link) {
            $formattedComments = array();
            $comments = $link->getComments();
            foreach ($comments as $comment) {
                array_push($formattedComments,["author" => $comment->getAuthorName(), "score" => ($comment->getUpvotes() - $comment->getDownvotes()), "comments" => $this->getReplies($comment)]);
            }

            $post = ["title" => $link->getTitle(), 'author' => $link->getAuthorName(), 'score' => $link->getScore(), 'comments' => $formattedComments];

            array_push($posts, $post);
        }

        dd($posts);
    }

    public function getReplies($comment) {

        $return = array();

        $replies = $comment->getReplies();

        foreach($replies as $reply) {
            array_push($return,["author" => $reply->getAuthorName(), "score" => ($reply->getUpvotes() - $reply->getDownvotes()), "comments" => $this->getReplies($reply)]);
        }

        return $return;
    }

}
