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

        if ($update->getChat()->getType() == "private") {
            $message = $update->getMessage()->getText();

            if(preg_match('/^(\/.+?)\s(.*)?$/', $message, $command) == 1) {
                switch(strtolower($command[1])) {
                    case "/start":
                        $this->startCommand($update);
                        break;
                }
            }
        }

        return response()->json(['success' => 'success'], 200);
    }

    public function setWebhook() {
        $response = \Telegram::setWebhook(['url' => 'https://108.61.90.113/552887591:AAFsyKGRvFZbVDPoSQtuw6uhjZHYefdnLNY/webhook']);

        dd($response);
    }

    function startCommand($update) {
        \Telegram::sendMessage([
            'chat_id' => $update->getMessage()->getChat()->getId(),
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
            'text' => "<b>Commands:</b>\n/start - Register for Airdrop"
        ]);
    }

}