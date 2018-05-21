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


Route::get('/twitter', 'BountyController@twitter')->name('twitter-get');
Route::post('/twitter', 'BountyController@twitterSubmit')->name('twitter-post');

Route::get('/twitter/{id}', 'BountyController@twitterReferral')->name('twitter-referral');

Route::get('/twitter-export', 'BountyController@twitterExport')->name('twitter-export');
Route::get('/twitter-get-retweet', 'BountyController@twitterGetRetweet')->name('twitter-retweet');
Route::get('/twitter-end', 'BountyController@twitterEnd')->name('twitter-end');