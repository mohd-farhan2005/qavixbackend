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
        // Sanitize ASSET_URL if it contains localhost or ends in /public
        $assetUrl = env('ASSET_URL');
        if (!empty($assetUrl)) {
            if (str_contains($assetUrl, 'localhost')) {
                config(['app.asset_url' => null]);
            } elseif (str_ends_with(rtrim($assetUrl, '/'), '/public')) {
                $cleaned = preg_replace('#/public/?$#', '', $assetUrl);
                config(['app.asset_url' => $cleaned ?: null]);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production or behind Railway SSL reverse proxy
        if (
            config('app.env') === 'production' ||
            env('APP_ENV') === 'production' ||
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            str_contains(request()->header('host', ''), 'railway.app')
        ) {
            URL::forceScheme('https');
        }
    }
}
