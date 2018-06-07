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
