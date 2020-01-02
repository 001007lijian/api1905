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
    return view('welcome');
});

Route::get('test/alipay','TestController@alipay');

Route::get('alipay/return','alipay\\PayController@return');//同步
Route::post('alipay/notify','alipay\\PayController@notify');//异步
