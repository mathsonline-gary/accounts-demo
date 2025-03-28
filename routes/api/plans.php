<?php

use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

Route::prefix('plans')
    ->name('plans.')
    ->group(function () {
        Route::post('/search', [PlanController::class, 'search'])->name('search');
    });
