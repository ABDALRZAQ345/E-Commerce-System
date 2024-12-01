<?php

use App\Http\Controllers\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        //   Route::get('/products/{product}/audits', [ProductController::class, 'audits'])->name('products.audits');
        Route::post('/products/{product}/rate', [ProductController::class, 'rate'])->name('products.rate');
    });

});
