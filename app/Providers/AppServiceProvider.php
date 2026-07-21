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
        \Illuminate\Support\Facades\Gate::define('admin-only', function ($user) {
            return strtolower($user->role ?? '') === 'admin';
        });

        \Illuminate\Support\Facades\Gate::define('not-kepala-dapur', function ($user) {
            return !in_array(strtolower($user->role ?? ''), ['kepala dapur', 'kepala sppg']);
        });
    }
}
