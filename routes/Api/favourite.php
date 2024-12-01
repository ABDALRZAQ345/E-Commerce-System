<?php

use App\Http\Controllers\Product\FavouriteProductController;
use App\Http\Controllers\Store\FavouriteStoreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::prefix('favourites/products')->middleware('auth:sanctum')->name('products.')->group(function () {
        Route::post('/add', [FavouriteProductController::class, 'addFavourite'])->name('addFavourite');
        Route::post('/remove', [FavouriteProductController::class, 'removeFavourite'])->name('removeFavourite');
        Route::get('/', [FavouriteProductController::class, 'getFavourites'])->name('getFavourites');
    });

    Route::prefix('favourites/stores')->middleware('auth:sanctum')->name('stores.')->group(function () {
        Route::post('/add', [FavouriteStoreController::class, 'addFavourite'])->name('addFavourite');
        Route::post('/remove', [FavouriteStoreController::class, 'removeFavourite'])->name('removeFavourite');
        Route::get('/', [FavouriteStoreController::class, 'getFavourites'])->name('getFavourites');
    });

});
