<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDevMode
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
        if(env('APP_ENV') === 'production') {
            if(env('IS_MULTI_TENNANT'))
            {
                return redirect('/kintai');
            }
            else
            {
                return redirect('/swk');
            }
        }
        return $next($request);
    }
}
