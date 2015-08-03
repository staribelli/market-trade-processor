<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::post('messages', ['middleware' => 'auth.basic', 'uses' => 'CurrencymessageController@store']);
Route::get('messages/list', 'CurrencymessageController@index');

Route::get('socket', 'CurrencymessageController@index');
Route::post('sendmessage', 'SocketController@sendMessage');
Route::get('writemessage', 'SocketController@writemessage');

Route::get('test', function () {
    event(new App\Events\QueueMessage());
    return "event fired";
});

Route::get('testview', 'CurrencymessageController@test');
Route::get('testevent', 'CurrencymessageController@testEvent');

Route::get('auth/login', 'AuthController@login');