<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\AppLibs\LogFunctions as Log;
use Illuminate\Support\Facades\Auth;

class ControllerLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //ユーザーID
        $user = Auth::user();
        $user_id = 0;
        if($user != null)
        {
            $user_id = $user->id;
        }
        //action特定配列

        $path = $request->path();
        
        Log::info(0, 0, $path, $user_id);
        return $next($request);
    }
}
