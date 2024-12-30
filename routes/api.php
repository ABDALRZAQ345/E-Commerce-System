<?php

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Stripe\StripeController;
use App\Http\Controllers\stripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/home', HomeController::class);
        Route::post('/create-payment-intent', [StripeController::class, 'createPaymentIntent']);
       Route::post('/stripe/webhook', [stripeWebhookController::class, 'handleWebhook']);

    });

});
