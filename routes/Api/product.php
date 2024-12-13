<?php

use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        //Route::get('/products/{product}/audits', [ProductController::class, 'audits'])->name('products.audits');
        Route::post('/products/{product}/review', [ProductReviewController::class, 'review'])->name('products.rate');
        Route::get('/products/{product}/reviews', [ProductReviewController::class, 'index'])->name('products.reviews');
    });

});
