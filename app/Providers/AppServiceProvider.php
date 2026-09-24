<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $isHttpsOrTunnel = config('app.env') === 'production'
            || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
            || (function_exists('request') && (request()->isSecure() || request()->header('X-Forwarded-Proto') === 'https'));

        if ($isHttpsOrTunnel) {
            URL::forceScheme('https');
            Vite::useHotFile(storage_path('vite.hot'));
        }
        $timezone = config('app.timezone', 'Asia/Jakarta');
        date_default_timezone_set($timezone);
        Carbon::setLocale(config('app.locale', 'id'));
    }
}
