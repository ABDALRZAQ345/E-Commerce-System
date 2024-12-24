<?php

use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/home', function (Request $request) {
            $recommended_products = Product::filter('recommended')->take(10)->get();
            $latest_products = Product::filter('latest')->take(10)->get();
            $top_products = Product::filter('top_rated')->take(10)->get();
            $latest_stores = Store::filter('latest')->take(10)->get();
            $top_stores = Store::filter('top_rated')->take(10)->get();

            return response()->json([
                'recommended_products' => $recommended_products,
                'latest_products' => $latest_products,
                'top_products' => $top_products,
                'latest_stores' => $latest_stores,
                'top_stores' => $top_stores,
            ]);
        });
        Route::post('/create-payment-intent', [\App\Http\Controllers\StripeController::class, 'createPaymentIntent']);
    });

});
