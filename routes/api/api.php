<?php

Route::prefix('v1')
    ->name('v1.')
    ->group(function () {
        require __DIR__.'/auth.php';
        require __DIR__.'/plans.php';
        require __DIR__.'/orders.php';
    });
