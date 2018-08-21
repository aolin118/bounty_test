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
                        $this->startCommand($update, $command);
                        break;
                }
            }
        }

        return response()->json(['success' => 'success'], 200);
    }

    public function setWebhook() {
        $response = \Telegram::setWebhook(['url' => 'https://bounty.bcoinsg.io/657492216:AAHcY1vdwp7H33JtwzrYlVKu2qCznzCSJ2o/webhook']);

        dd($response);
    }

    function startCommand($update, $command) {
        $id = $update['message']['from']['id'];

        $user = BountyUser::where("unique_link",trim($command[2]))->first();

        if ($user) {
            $telegramUser = new TelegramUser;
            $telegramUser->bounty_user_id = $user->id;
            $telegramUser->telegram_id = $id;
            $saved = $telegramUser->save();

            if ($saved) {
                \Telegram::sendMessage([
                    'chat_id' => $update->getMessage()->getChat()->getId(),
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                    'text' => "<b>You have successfully registered for the bounty!</b>\n\nVisit https://bounty.bcoin.sg to check your tokens earned!\n\nYour referral link:\nhttps://bounty.bcoin.sg/r/" . $user->unique_link
                ]);
            }
        } else {
            \Telegram::sendMessage([
                'chat_id' => $update->getMessage()->getChat()->getId(),
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
                'text' => "<b>BCoin Bounty Program is ongoing!</b>\nVisit https://bounty.bcoin.sg to participate!"
            ]);
        }
    }

}