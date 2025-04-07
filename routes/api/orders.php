<?php

use App\Http\Controllers\OrderCheckoutController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')
    ->name('orders.')
    ->group(function () {
        // Public routes. Some of them should be moved to the authenticated routes in the future for better security.
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{uuid}', [OrderController::class, 'show'])->name('show');

        // Checkout routes.
        Route::post('/{uuid}/checkouts', [OrderCheckoutController::class, 'store'])->name('checkout.store');

        // Authenticated routes
        Route::middleware('auth:api')->group(function () {
            Route::post('/search', [OrderController::class, 'search'])->name('search');
        });
    });
