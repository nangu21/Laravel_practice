<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class HelloController extends Controller
{
    //public function index() {
    //    $data = [
    //        'msg' => 'this is sample message.',
    //    ];
    //    return view('hello.index', $data);
    //}

    //public function other() {
    //    return redirect()->route('hello'); //wep.phpにて設定した名前だけでルート情報が取り出せる
    //}

    //public function index($id) {
    //    $data = [
    //        'msg' => 'id = '. $id,
    //    ];
    //    return view('hello.index', $data);
    //}

    //public function index(Request $request) {
    //    $data = [
    //        'msg' => $request->hello, //HelloMiddlewareで設定
    //    ];
    //    return view('hello.index', $data);
    //}
//
    //public function other(Request $request) {
    //    $data = [
    //        'msg' => $request->bye, //HelloMiddlewareで設定
    //    ];
    //    return view('hello.index', $data);
    //}

    //public function index(Person $person) {
    //public function index($person) { //RouteServiceProviderでクラスを措定後
    //    $data = [
    //        'msg' => $person,
    //    ];
    //    return view('hello.index', $data);
    //}

    public function index() {
        $sample_msg = config('sample.message');
        $sample_data = config('sample.data');
        $data = [
            'msg' => $sample_msg,
            'data' => $sample_data,
        ];
        return view('hello.index', $data);
    }

    //function __construct() {  //config内の変数を上書きする
    //    config(['sample.message'=>'新しいメッセージ！']);
    //}
}
