<?php

use App\Http\Controllers\Statistics\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('/statistics', [StatisticsController::class, 'get']);
    });
});
