<?php

use App\Http\Controllers\StatisticsController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware(['auth:sanctum', EnsureIsAdmin::class])->group(function () {
        Route::get('/statistics', [StatisticsController::class, 'get']);
    });
});
