<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Paksa HTTPS hanya jika request datang lewat proxy/SSL (ngrok, Cloudflare, Nginx SSL di VPS).
        // Tidak lagi memaksa HTTPS di lokal biasa agar tidak menyebabkan redirect loop.
        if (request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}