<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\FcmTokenController;
use App\Http\Controllers\Promotion\PromotionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::post('/password/forget', [PasswordController::class, 'forget'])->middleware('throttle:change_password')->name('forget_password');

    Route::middleware('guest')->group(function () {
        Route::post('/verificationCode/send', [VerificationCodeController::class, 'send'])->middleware('throttle:send_confirmation_code')->name('verificationCode.check');

        Route::post('/verificationCode/check', [VerificationCodeController::class, 'check'])->name('verificationCode.check');

        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register')->name('register');

        Route::post('/login', [AuthController::class, 'login'])->name('login');

    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('password/reset', [PasswordController::class, 'reset'])->name('password.reset');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/send_fcm', [FcmTokenController::class, 'send']);

        Route::post('/promotions/create', [PromotionController::class, 'create'])->name('promotions.create');
        Route::post('/promotions/check', [PromotionController::class, 'check'])->name('promotions.check');
    });

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/promotions/{promotion}/accept', [PromotionController::class, 'promote'])->name('promote');

        Route::get('/promotions', [PromotionController::class, 'index'])->name('Promotions.index');

        Route::post('/promotions/{promotion}/reject', [PromotionController::class, 'reject'])->name('reject');

    });

});
