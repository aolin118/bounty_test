<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TelegramUser;
use Log;
use Telegram\Bot\Api;
use Carbon\Carbon;

class BotController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Api $telegram)
    {
      $this->telegram = $telegram;
    }

    public function receiveCallback() {
        Log::info('Webhook callback received!');
        //$update = \Telegram::commandsHandler(true);
        $update = \Telegram::getWebhookUpdates();
        Log::info($update);
        
        if ($update['message']['chat']['type'] == "private") {
            $message = $update->getMessage()->getText();

            if(preg_match('/^(\/.+?)(\s.*)?$/', $message, $command) == 1) {
                switch(strtolower($command[1])) {
                    case "/start":
                        $this->startCommand($update, $command[2]);
                        break;
                }
            }
        }

        return response()->json(['success' => 'success'], 200);
    }

    public function setWebhook() {
        $response = \Telegram::setWebhook(['url' => 'https://xaneau.com/552887591:AAFsyKGRvFZbVDPoSQtuw6uhjZHYefdnLNY/webhook']);

        dd($response);
    }

    function startCommand($update, $code) {
        $user = TelegramUser::where("unique_link",$code)->first();
        Log::info("here");
        if ($user) {
            $user->telegram_id = $update['message']['from']['id'];
            $saved = $user->save();

            if ($saved) {
                \Telegram::sendMessage([
                    'chat_id' => $update->getMessage()->getChat()->getId(),
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                    'text' => "<b>You have successfully registered for the airdrop!</b>\n\nVisit https://xaneau.com to check your tokens earned!\n\nYour referral link:\nhttps://xaneau.com/r/" . $user->telegram_id
                ]);
            }

        } else {
            \Telegram::sendMessage([
                'chat_id' => $update->getMessage()->getChat()->getId(),
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
                'text' => "<b>Source Code Chain Airdrop is ongoing!</b>\nVisit https://xaneau.com to participate!"
            ]);
        }

        
    }

}