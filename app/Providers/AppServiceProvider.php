<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // log sql queries if debug is enabled
        if (config('app.debug')) {
            DB::listen(function ($query) {
                Log::debug('SQL Query', [
                    'query' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                ]);
            });
        }

        // Force HTTPS Scheme in non-local environments.
        if (! app()->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
