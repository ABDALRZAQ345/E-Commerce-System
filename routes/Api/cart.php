<?php

use App\Http\Controllers\CartItemController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:sanctum'])->group(function () {
    Route::get('/cartItems', [CartItemController::class, 'index']);
    Route::post('/cartItems', [CartItemController::class, 'store']);
    Route::delete('/cartItems/{cartItem}', [CartItemController::class, 'delete']);

});
