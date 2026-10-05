<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Behind Vercel's HTTPS edge, always generate https:// asset and route URLs
        // to avoid browsers blocking CSS/JS as mixed content.
        if ($this->app->environment('production') || getenv('VERCEL')) {
            URL::forceScheme('https');
        }
    }
}
