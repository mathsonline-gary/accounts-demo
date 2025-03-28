<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('orders')
    ->name('orders.')
    ->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('/', [OrderController::class, 'store'])->name('store');
            Route::get('/{uuid}', [OrderController::class, 'show'])->name('show');
            Route::post('/search', [OrderController::class, 'search'])->name('search');
        });
    });
