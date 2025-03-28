<?php

use App\Http\Controllers\Auth\AuthenticatedTokenController;
use App\Http\Controllers\Auth\AuthenticatedUserController;
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
