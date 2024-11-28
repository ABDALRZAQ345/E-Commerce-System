<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItemController;
use App\Http\Controllers\Order\SubOrderController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Store\ContactController;
use App\Http\Controllers\Store\StoreCategoryController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Store\StoreProductController;
use App\Http\Controllers\StoreOrderController;
use App\Http\Controllers\Test;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/stores', [StoreController::class, 'index'])->name('stores');
        Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
        Route::post('/stores', [StoreController::class, 'store'])->middleware('can_create_store')->name('stores.store');
        Route::post('/stores/{store}', [StoreController::class, 'update'])->middleware('store_owner')->name('stores.update');
       // Route::get('/stores/{store}/audits', [StoreController::class, 'audits'])->name('stores.audits');
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
        //todo Route::get('/stores/{store}/orders', [StoreOrderController::class, 'index'])->middleware('store_owner')->name('stores.products.index');
        ///
        Route::get('/categories',[CategoryController::class, 'index'])->name('categories.index');
        ///
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        ///

        Route::get('/users/{user}/orders', [OrderController::class, 'index'])->middleware('same_user')->name('orders.index');
        Route::get('/users/{user}/orders/{order}', [OrderController::class, 'show'])->middleware('same_user')->name('orders.show');
        Route::get('/users/{user}/orders/{order}/suborders/{sub_order}', [SubOrderController::class, 'show'])->middleware('same_user')->name('orders.suborder.show');
        Route::get('/users/{user}/orders/{order}/suborders', [SubOrderController::class, 'index'])->middleware('same_user')->name('orders.suborder.index');
        Route::get('/users/{user}/orders/{order}/suborders/{sub_order}/items', [OrderItemController::class, 'index'])->middleware('same_user')->name('orders.suborder.show');
        Route::post('/users/{user}/orders', [OrderController::class, 'store'])->middleware('same_user')->name('orders.store');

    });

});

Route::post('/tmp_login', [Test::class, 'tmp_login'])->name('tmp-login');
Route::get('/', [Test::class,'index'])->name('index');
