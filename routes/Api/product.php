<?php

use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:sanctum'])->group(function () {

    Route::prefix('/products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::post('/{product}/review', [ProductReviewController::class, 'review'])->name('reviews.store');
        Route::get('/{product}/reviews', [ProductReviewController::class, 'index'])->name('reviews.index');
        //Route::get('/products/{product}/audits', [ProductController::class, 'audits'])->name('products.audits');
    });

});
