<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\Promotion\PromotionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss','auth:sanctum'])->group(function () {
Route::get('/cartItems',[\App\Http\Controllers\CartItemController::class,'index']);
Route::post('/cartItems',[\App\Http\Controllers\CartItemController::class,'store']);
Route::delete('/cartItems/{cartItem}',[\App\Http\Controllers\CartItemController::class,'delete']);

});
