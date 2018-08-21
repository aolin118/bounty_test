<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('twitter');
});


Route::get('/', 'BountyController@index')->name('bounty-home');

Route::get('/a/{eth_address}', 'BountyController@addressSubmit')->name('bounty-submit-get');
Route::post('/a/{eth_address}', 'BountyController@addressSubmitWithReferral')->name('bounty-submit-post');

Route::get('/twitter-callback', 'BountyController@twitterCallback')->name('bounty-twitter-callback');
Route::get('/youtube-callback', 'BountyController@youtubeCallback')->name('bounty-youtube-callback');
Route::get('/reddit-callback', 'BountyController@redditCallback')->name('bounty-reddit-callback');
Route::get('/medium-callback', 'BountyController@mediumCallback')->name('bounty-medium-callback');

Route::get('/telegram-verify', 'BountyController@telegramVerify')->name('bounty-telegram-verify');
Route::get('/twitter-verify', 'BountyController@twitterVerify')->name('bounty-twitter-verify');
Route::get('/youtube-verify', 'BountyController@youtubeVerify')->name('bounty-youtube-verify');
Route::get('/reddit-verify', 'BountyController@redditVerify')->name('bounty-reddit-verify');
Route::get('/medium-verify', 'BountyController@mediumVerify')->name('bounty-medium-verify');

Route::get('/r/{referral}', 'BountyController@bountyReferral')->name('bounty-referral');


Route::get('/airdrop-export', 'AirdropController@airdropExport')->name('airdrop-export');

// Telegram Bot Routes
Route::post('/657492216:AAHcY1vdwp7H33JtwzrYlVKu2qCznzCSJ2o/webhook', 'BotController@receiveCallback')->name('bot-webhook');
Route::get('/657492216:AAHcY1vdwp7H33JtwzrYlVKu2qCznzCSJ2o/set-webhook', 'BotController@setWebhook')->name('bot-set-webhook');
