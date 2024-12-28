<?php

use App\Http\Controllers\Store\ContactController;
use App\Http\Controllers\Store\StoreCategoryController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Store\StoreOrderController;
use App\Http\Controllers\Store\StoreProductController;
use App\Http\Controllers\Store\StoreReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:sanctum'])->prefix('stores')->name('stores.')->group(function () {

    Route::get('/', [StoreController::class, 'index'])->name('index');
    Route::post('/', [StoreController::class, 'store'])->middleware('can_create_store')->name('store');

    Route::middleware('store_owner')->prefix('/{store}')->group(function () {

        Route::post('', [StoreController::class, 'update'])->name('update');
        Route::post('/products', [StoreProductController::class, 'store'])->name('products.store');
        Route::post('/products/{product}', [StoreProductController::class, 'update'])->name('products.update');
        Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
        Route::delete('/contacts/{contact}', [ContactController::class, 'delete'])->name('contacts.delete');
        Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
        Route::put('/categories', [StoreCategoryController::class, 'update'])->name('categories.update');
        Route::get('/orders', [StoreOrderController::class, 'index'])->name('orders.index');
        Route::post('/orders/{order}', [StoreOrderController::class, 'update'])->name('orders.update');
        //Route::get('/audits', [StoreController::class, 'audits'])->name('audits');
    });

    Route::prefix('/{store}')->group(function () {
        Route::get('', [StoreController::class, 'show'])->name('show');
        Route::get('/products', [StoreProductController::class, 'index'])->name('products.index');
        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::get('/categories', [StoreCategoryController::class, 'index'])->name('categories.index');
        Route::post('/review', [StoreReviewController::class, 'review'])->name('reviews.store');
        Route::get('/reviews', [StoreReviewController::class, 'index'])->name('reviews.index');
    });

});
