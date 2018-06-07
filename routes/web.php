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


Route::get('/', 'AirdropController@index')->name('airdrop-get');
Route::post('/', 'AirdropController@addressSubmit')->name('airdrop-post');
Route::get('/r/{id}', 'AirdropController@airdropReferral')->name('airdrop-referral');
Route::get('/airdrop-export', 'AirdropController@airdropExport')->name('airdrop-export');

// Telegram Bot Routes
Route::post('/552887591:AAFsyKGRvFZbVDPoSQtuw6uhjZHYefdnLNY/webhook', 'BotController@receiveCallback')->name('bot-webhook');
Route::get('/552887591:AAFsyKGRvFZbVDPoSQtuw6uhjZHYefdnLNY/set-webhook', 'BotController@setWebhook')->name('bot-set-webhook');
