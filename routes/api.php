<?php

use Illuminate\Support\Facades\Route;
use SomarKesen\TelegramGateway\Facades\TelegramGateway;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware('auth:sanctum')->group(function () {});

});


