<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Database\Eloquent\Model;
use App\Http\AppLibs\LogFunctions as Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

     /**
     * ログアウトしたときの画面遷移先
     */
    protected function loggedOut(\Illuminate\Http\Request $request)
    {
        if(env('SHACHIHATA'))
        {
            return redirect('/swk');
        }
        else
        {
            return redirect('/kintai');
        }
    }
    public function username()
    {
        return 'employee_id';
    }

    /**
     * ログイン後に実行される
     */
    protected function authenticated(Request $request, $user)
    {

        $user_id = $user->id;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        Log::info(1, 1, "ログイン成功(". $user_agent . ")", $user_id);
    }
}
