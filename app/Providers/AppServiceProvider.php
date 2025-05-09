<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        VerifyEmail::createUrlUsing(function (object $notifiable) {
            $id = $notifiable->getKey();
            $hash = sha1($notifiable->getEmailForVerification());

            $user = User::find($id);
            $brand = $user->brand;
            $baseUrl = match ($user->role) {
                UserRole::ADMIN => $brand->admin_website_url,
                UserRole::TEACHER => $brand->teacher_website_url,
                UserRole::STUDENT => $brand->student_website_url,
            };

            $expiresAt = Carbon::now()->addMinutes(60)->getTimestamp();
            $url = "$baseUrl/verify-email/$id/$hash?expires=$expiresAt";
            $signature = hash_hmac('sha256', $url, config('app.key'));

            return "$url&signature=$signature";
        });
    }
}
