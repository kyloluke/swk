<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $is_production = env('APP_ENV') === 'production';
        View::share('is_production', $is_production); 
        $is_https = env('USE_HTTPS');
        View::share('is_https', $is_https); 
        $is_multitennant = env('IS_MULTI_TENNANT');
        View::share('is_multitennant', $is_multitennant); 
        if (env('USE_HTTPS', true)) {
            URL::forceScheme('https');
        }
    }
}
