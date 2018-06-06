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
        $response = \Telegram::setWebhook(['url' => 'https://xaneau.com/552887591:AAFsyKGRvFZbVDPoSQtuw6uhjZHYefdnLNY/webhook']);

        dd($response);
    }

    function startCommand($update, $command) {

        $id = $update['message']['from']['id'];
        $user = TelegramUser::where("telegram_id", $id)->first();

        if ($user) {
            \Telegram::sendMessage([
                    'chat_id' => $update->getMessage()->getChat()->getId(),
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                    'text' => "<b>You have successfully registered for the airdrop!</b>\n\nVisit https://xaneau.com to check your tokens earned!\n\nYour referral link:\nhttps://xaneau.com/r/" . $user->telegram_id
                ]);
        } else {
            if (isset($command[2])) {
                $group = "@xane_bots";

                $chatMember = \Telegram::getChatMember(['chat_id' => $group, 'user_id' => $id]);

                if (is_null($chatMember)) {
                    Log::info("true");
                } else {
                    Log::info($chatMember['status']);
                }
                

                $user = TelegramUser::where("unique_link",trim($command[2]))->first();

                if ($user) {
                    $user->telegram_id = $id;
                    $saved = $user->save();

                    if ($saved) {
                        \Telegram::sendMessage([
                            'chat_id' => $update->getMessage()->getChat()->getId(),
                            'parse_mode' => 'HTML',
                            'disable_web_page_preview' => true,
                            'text' => "<b>You have successfully registered for the airdrop!</b>\n\nVisit https://xaneau.com to check your tokens earned!\n\nYour referral link:\nhttps://xaneau.com/r/" . $user->telegram_id
                        ]);
                    }
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

}