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

Route::get('/', 'BountyController@end')->name('bounty-home');

Route::get('/main', 'BountyController@end')->name('bounty-submit-get');
Route::post('/main', 'BountyController@end')->name('bounty-submit-post');

Route::get('/logout', 'BountyController@logOut')->name('bounty-logout');

Route::get('/twitter-callback', 'BountyController@twitterCallback')->name('bounty-twitter-callback');
Route::get('/youtube-callback', 'BountyController@youtubeCallback')->name('bounty-youtube-callback');
Route::get('/reddit-callback', 'BountyController@redditCallback')->name('bounty-reddit-callback');
Route::get('/medium-callback', 'BountyController@mediumCallback')->name('bounty-medium-callback');

Route::get('/telegram-verify', 'BountyController@telegramVerify')->name('bounty-telegram-verify');
Route::get('/twitter-verify', 'BountyController@twitterVerify')->name('bounty-twitter-verify');
Route::get('/youtube-verify', 'BountyController@youtubeVerify')->name('bounty-youtube-verify');
Route::get('/reddit-verify', 'BountyController@redditVerify')->name('bounty-reddit-verify');
Route::get('/medium-verify', 'BountyController@mediumVerify')->name('bounty-medium-verify');
Route::post('/facebook-verify', 'BountyController@facebookVerify')->name('bounty-facebook-verify');
Route::post('/instagram-verify', 'BountyController@instagramVerify')->name('bounty-instagram-verify');
Route::post('/linkedin-verify', 'BountyController@linkedInVerify')->name('bounty-linkedin-verify');
Route::get('/interest-change', 'BountyController@interestChange')->name('bounty-interest-change');
Route::get('/newsletter-change', 'BountyController@newsletterChange')->name('bounty-newsletter-change');

Route::get('/r/{referral}', 'BountyController@end')->name('bounty-referral');


Route::get('/airdrop-export', 'AirdropController@airdropExport')->name('airdrop-export');

// Telegram Bot Routes
Route::post('/657492216:AAHcY1vdwp7H33JtwzrYlVKu2qCznzCSJ2o/webhook', 'BotController@receiveCallback')->name('bot-webhook');
Route::post('/657492216:AAHcY1vdwp7H33JtwzrYlVKu2qCznzCSJ2o/webhook2', 'BotController@receiveCallback2')->name('bot-webhook2');
Route::get('/657492216:AAHcY1vdwp7H33JtwzrYlVKu2qCznzCSJ2o/set-webhook', 'BotController@setWebhook')->name('bot-set-webhook');
