<?php

use Illuminate\Support\Facades\Route;

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

//ビュールート
//Route::view(uri, view, option(viewに渡す値の連想配列など));
Route::get('/', function () {
    return view('welcome');
});

//ルート定義メソッド
//Route::get(uri, callback);
//Route::post();
//Route::put();
//Route::patch();
//Route::delete();
//Route::options();
Route::get('/hello', 'App\Http\Controllers\HelloController@index');

//名前付きルート
//Route::get()->name();
//Route::get('/hello', 'App\Http\Controllers\HelloController@index')->name('hello');
//Route::get('/hello/other', 'App\Http\Controllers\HelloController@other');

//whereによる正規表現ルート(パラメータに制約をかける)
//[Route]->where(param, pattern);
//Route::get('/hello/{id}', 'App\Http\Controllers\HelloController@index')->where('id', '[0-9]+');

//ミドルウェアの割り当て
//ミドルウェア：Httpリクエストの入り口と出口に独自の機能・処理を立てる
//複数のルートについてミドルウェアを組み込む必要があるとき、groupルート(引数に関数)が便利
//Route::middleware([App\Http\Middleware\HelloMiddleware::class])->group(function() {
//    Route::get('/hello', 'App\Http\Controllers\HelloController@index');
//    Route::get('/hello/other', 'App\Http\Controllers\HelloController@other');
//});

//名前空間とグループルート
Route::namespace('App\Http\Controllers\Sample')->group(function() { //名前空間まではフルパス
    Route::get('/sample', 'SampleController@index'); //あとはコントローラ名とメソッド名だけでOK
    Route::get('/sample/other', 'SampleController@other');
});

//RouteとModelの結合
//Route::get('/hello/{person}', 'App\Http\Controllers\HelloController@index');