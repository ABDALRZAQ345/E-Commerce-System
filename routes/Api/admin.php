<?php

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItemController;
use App\Http\Controllers\Order\SubOrderController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserInterestController;
use App\Http\Controllers\User\UserLocationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:sanctum','role:admin'])->group(function () {
        Route::delete('/stores/{store}',[StoreController::class,'delete']);
        Route::delete('/users/{user}',[UserController::class,'delete']);
});
