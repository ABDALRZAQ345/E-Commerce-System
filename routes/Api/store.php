<?php

use App\Http\Controllers\Store\ContactController;
use App\Http\Controllers\Store\StoreCategoryController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Store\StoreOrderController;
use App\Http\Controllers\Store\StoreProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/stores', [StoreController::class, 'index'])->name('stores');
        Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
        Route::post('/stores', [StoreController::class, 'store'])->middleware('can_create_store')->name('stores.store');
        Route::post('/stores/{store}', [StoreController::class, 'update'])->middleware('store_owner')->name('stores.update');
        Route::get('/stores/{store}/audits', [StoreController::class, 'audits'])->name('stores.audits');
        Route::get('/stores/{store}/products', [StoreProductController::class, 'index'])->name('stores.products.index');
        Route::get('/stores/{store}/products/{product}', [StoreProductController::class, 'show'])->name('stores.products.show');
        Route::post('/stores/{store}/products', [StoreProductController::class, 'store'])->middleware('store_owner')->name('stores.products.store');
        Route::post('/stores/{store}/products/{product}', [StoreProductController::class, 'update'])->middleware('store_owner')->name('stores.products.update');
        Route::get('/stores/{store}/contacts', [ContactController::class, 'index'])->name('stores.products.contacts');
        Route::post('/stores/{store}/contacts', [ContactController::class, 'store'])->middleware('store_owner')->name('stores.products.contacts.store');
        Route::delete('/stores/{store}/contacts/{contact}', [ContactController::class, 'delete'])->middleware('store_owner')->name('stores.products.contacts.delete');
        Route::put('/stores/{store}/contacts/{contact}', [ContactController::class, 'update'])->middleware('store_owner')->name('stores.products.contacts.update');
        Route::get('/stores/{store}/categories', [StoreCategoryController::class, 'index'])->name('stores.categories.index');
        //Route::post('/stores/{store}/categories', [StoreCategoryController::class, 'store'])->middleware('store_owner')->name('stores.categories.store');
        Route::put('/stores/{store}/categories', [StoreCategoryController::class, 'update'])->middleware('store_owner')->name('stores.categories.update');
        Route::get('/stores/{store}/orders', [StoreOrderController::class, 'index'])->middleware('store_owner')->name('stores.products.index');
        Route::post('/stores/{store}/orders/{order}', [StoreOrderController::class, 'update'])->middleware('store_owner')->name('stores.products.update');

    });

});
