<?php

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItemController;
use App\Http\Controllers\Order\SubOrderController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserInterestController;
use App\Http\Controllers\User\UserLocationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:sanctum'])->prefix('/users')->name('users.')->group(function () {

    Route::middleware('same_user')->prefix('/{user}')->group(function () {

        Route::prefix('/orders')->name('orders.')->group(function () {
            Route::get('', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::get('/{order}/suborders/{sub_order}', [SubOrderController::class, 'show'])->name('suborder.show');
            Route::get('/{order}/suborders', [SubOrderController::class, 'index'])->name('suborder.index');
            Route::get('/{order}/suborders/{sub_order}/items', [OrderItemController::class, 'index'])->name('suborder.show');
            Route::post('', [OrderController::class, 'store'])->name('store');
        });

        Route::post('/update', [UserController::class, 'update'])->name('update');
        Route::get('/suborders', [SubOrderController::class, 'all_orders'])->name('orders.suborder.index');
        Route::get('/locations', [UserLocationController::class, 'index'])->name('locations.index');
        Route::post('/locations', [UserLocationController::class, 'store'])->name('locations.store');
        Route::post('/locations/{location}', [UserLocationController::class, 'update'])->name('locations.update');
        Route::post('/categories', [UserInterestController::class, 'store'])->name('categories.store');
        Route::get('/categories', [UserInterestController::class, 'index'])->name('categories.index');

    });
    Route::get('', [UserController::class, 'index'])->middleware('role:admin')->name('index');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');

});
