<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\MailVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale','xss'])->group(function () {

    Route::middleware('guest')->group(function () {
        Route::post('/forget_password', [ChangePasswordController::class, 'ForgetPassword'])->name('forget_password');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::put('/update', [AuthController::class, 'update'])->name('update');
        Route::post('/resend_verification_code', [])->middleware('throttle:email_verification')->name('resend-email-verification-link');
    });

});
