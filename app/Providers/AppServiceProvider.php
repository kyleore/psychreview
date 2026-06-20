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
        // Behind Render's HTTPS proxy the app sees plain HTTP, which makes
        // route()/url() generate insecure http:// links (blocked as mixed
        // content). Force HTTPS for all generated URLs in production.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
