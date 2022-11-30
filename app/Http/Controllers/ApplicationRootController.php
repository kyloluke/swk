<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ApplicationRootController extends Controller
{
    //
    public function appRoot(Request $request)
    {
        //ログイン中かチェック
        if (Auth::check())
        {
            //ログイン中だったらログアウト実行
            Auth::logout();
        }
        //env、route指定との一致を確認
        if((url()->current() === "/swk" && !env("IS_MULTI_TENNANT"))
            || (Session::has("is_multitennant") && !session("is_multitennant")))
        {
            //シヤチハタ版
            return view("app")->with("is_multitennant", false);
        }
        else if ((url()->current() === "/kintai" && env("IS_MULTI_TENNANT"))
            || (Session::has("is_multitennant") && session("is_multitennant")))
        {
            //マルチテナント版
            return view("app")->with("is_multitennant", true);
        }
        else
        {
            //不一致の場合リダイレクト
            if(env("IS_MULTI_TENNANT"))
            {
                //getパラメータ取得して再付加
                $param = $request->input("ccode");
                $url = $param === null ? "kintai" : "kintai?ccode=" . $param;
                return redirect($url)->with("is_multitennant", true);
            }
            else
            {
                return redirect("swk")->with("is_multitennant", false);
            }
        }
    }
}
