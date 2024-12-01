<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItemController;
use App\Http\Controllers\Order\SubOrderController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserLocationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/users', [UserController::class, 'index'])->middleware('role:admin')->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->middleware('role:admin')->name('users.show');
        Route::get('/users/{user}/orders', [OrderController::class, 'index'])->middleware('same_user')->name('orders.index');
        Route::get('/users/{user}/orders/{order}', [OrderController::class, 'show'])->middleware('same_user')->name('orders.show');
        Route::get('/users/{user}/orders/{order}/suborders/{sub_order}', [SubOrderController::class, 'show'])->middleware('same_user')->name('orders.suborder.show');
        Route::get('/users/{user}/orders/{order}/suborders', [SubOrderController::class, 'index'])->middleware('same_user')->name('orders.suborder.index');
        Route::get('/users/{user}/orders/{order}/suborders/{sub_order}/items', [OrderItemController::class, 'index'])->middleware('same_user')->name('orders.suborder.show');
        Route::post('/users/{user}/orders', [OrderController::class, 'store'])->middleware('same_user')->name('orders.store');
        Route::get('/users/{user}/locations',[UserLocationController::class, 'index'])->middleware('same_user')->name('locations.index');
        Route::post('/users/{user}/locations', [UserLocationController::class, 'store'])->middleware('same_user')->name('locations.store');
    });

});
