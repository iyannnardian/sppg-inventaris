<?php

namespace App\Providers;

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
        if ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || str_contains($_SERVER['HTTP_HOST'] ?? '', 'ngrok')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\Gate::define('admin-only', function ($user) {
            return strtolower($user->role ?? '') === 'admin';
        });

        \Illuminate\Support\Facades\Gate::define('not-kepala-dapur', function ($user) {
            return strtolower($user->role ?? '') !== 'kepala dapur';
        });
    }
}
