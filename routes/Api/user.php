<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItemController;
use App\Http\Controllers\Order\SubOrderController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserLocationController;
use App\Http\Controllers\UserInterestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/users', [UserController::class, 'index'])->middleware('role:admin')->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/update', [UserController::class, 'update'])->middleware('same_user')->name('update');

        Route::get('/users/{user}/orders', [OrderController::class, 'index'])->middleware('same_user')->name('orders.index');
        Route::get('/users/{user}/orders/{order}', [OrderController::class, 'show'])->middleware('same_user')->name('orders.show');
        Route::get('/users/{user}/orders/{order}/suborders/{sub_order}', [SubOrderController::class, 'show'])->middleware('same_user')->name('orders.suborder.show');
        Route::get('/users/{user}/orders/{order}/suborders', [SubOrderController::class, 'index'])->middleware('same_user')->name('orders.suborder.index');
        Route::get('/users/{user}/orders/{order}/suborders/{sub_order}/items', [OrderItemController::class, 'index'])->middleware('same_user')->name('orders.suborder.show');
        Route::post('/users/{user}/orders', [OrderController::class, 'store'])->middleware('same_user')->name('orders.store');
        Route::get('/users/{user}/locations', [UserLocationController::class, 'index'])->middleware('same_user')->name('locations.index');
        Route::post('/users/{user}/locations', [UserLocationController::class, 'store'])->middleware('same_user')->name('locations.store');
        Route::post('/users/{user}/locations/{location}',[UserLocationController::class, 'update'])->middleware('same_user')->name('locations.update');
        Route::post('/users/{user}/categories',[UserInterestController::class,'store'])->middleware('same_user')->name('categories.store');
        Route::get('/users/{user}/categories', [UserInterestController::class, 'index'])->middleware('same_user')->name('categories.index');
        Route::get('/users/{user}/recommended', [UserInterestController::class, 'recommend'])->middleware('same_user')->name('interests.index');
    });

});
