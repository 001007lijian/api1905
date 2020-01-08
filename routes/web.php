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
Route::get('/goods','TestController@goods');
Route::get('/goods2','TestController@goods2');
Route::get('/test/grab','TestController@grab');

Route::get('alipay/return','alipay\\PayController@return');//同步
Route::post('alipay/notify','alipay\\PayController@notify');//异步


Route::get('/api/test','api\TestController@test');

Route::post('/api/user/reg','api\TestController@reg');          //用户注册
Route::post('/api/user/login','api\TestController@login');      //用户登录
Route::get('/api/user/list','api\TestController@userList');      //用户列表
