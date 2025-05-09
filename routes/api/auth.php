<?php

use App\Http\Controllers\Auth\AuthenticatedTokenController;
use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\OAuthRegisteredUserController;
use App\Http\Controllers\Auth\OAuthTokenController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::middleware(['guest:api'])->group(function () {
            Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
            Route::post('/login', [AuthenticatedTokenController::class, 'store'])->name('login');
            Route::post('/refresh', [AuthenticatedTokenController::class, 'refresh'])->name('refresh');
        });

        Route::middleware(['auth:api'])->group(function () {
            Route::post('/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

            Route::post('/logout', [AuthenticatedTokenController::class, 'destroy'])->name('logout');
            Route::get('/me', [AuthenticatedUserController::class, 'show'])->name('me');
        });
    });

Route::prefix('oauth')
    ->name('oauth.')
    ->group(function () {
        Route::get('/{provider}', [OAuthTokenController::class, 'index'])->name('index');
        Route::post('/{provider}/register', [OAuthRegisteredUserController::class, 'store'])->name('register');
        Route::post('/{provider}/login', [OAuthTokenController::class, 'store'])->name('login');
    });
