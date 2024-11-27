<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\MailVerificationController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale','xss'])->group(function () {

    Route::middleware('guest')->group(function () {
        Route::post('/send_verificationCode', [VerificationController::class, 'sendVerificationCode'])->middleware('throttle:send_confirmation_code')->name('send_verificationCode');
        Route::post('/resend_verificationCode', [VerificationController::class, 'sendVerificationCode'])->middleware('throttle:send_confirmation_code')->name('resend_verification_code');
        Route::post('/check_verificationCode', [VerificationController::class, 'checkVerificationCode'])->name('check_verificationCode');

        Route::post('/forget_password', [VerificationController::class, 'sendVerificationCode'])->middleware('throttle:send_confirmation_code')->name('forget_password');
        Route::patch('/change_password', [ChangePasswordController::class, 'ChangePassword'])->name('change_password');

        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::put('/update', [AuthController::class, 'update'])->name('update');
        Route::post('/promotions/create', [PromotionController::class, 'create'])->name('promotions.create');
    });

    Route::middleware(['auth:sanctum', EnsureIsAdmin::class])->group(function () {
        Route::post('/promotions', [PromotionController::class, 'promote'])->name('promote');
        Route::get('/promotions', [PromotionController::class, 'index'])->name('Promotions.index');
    });

});
