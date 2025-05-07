<?php

use App\Http\Controllers\Auth\AuthenticatedTokenController;
use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\OAuthTokenController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
        Route::post('/login', [AuthenticatedTokenController::class, 'store'])->name('login');

        Route::middleware('auth:api')->group(function () {
            Route::get('/me', [AuthenticatedUserController::class, 'show'])->name('me');
            Route::post('/logout', [AuthenticatedTokenController::class, 'destroy'])->name('logout');
            Route::post('/refresh', [AuthenticatedTokenController::class, 'refresh'])->name('refresh');
        });
    });

Route::prefix('oauth')
    ->name('oauth.')
    ->group(function () {
        Route::get('/{provider}', [OAuthTokenController::class, 'index'])->name('index');
        Route::post('/{provider}/register', [OAuthTokenController::class, 'store'])->name('register');
        Route::post('/{provider}/login', [OAuthTokenController::class, 'show'])->name('login');
    });
