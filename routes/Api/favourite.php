<?php

use App\Http\Controllers\Product\FavouriteProductController;
use App\Http\Controllers\Store\FavouriteStoreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:sanctum'])->group(function () {

    Route::prefix('favourites/products')->name('products.')->group(function () {
        Route::post('/{product}', [FavouriteProductController::class, 'store'])->name('addFavourite');
        Route::delete('/{product}', [FavouriteProductController::class, 'delete'])->name('removeFavourite');
        Route::get('', [FavouriteProductController::class, 'index'])->name('getFavourites');
    });

    Route::prefix('favourites/stores')->name('stores.')->group(function () {
        Route::post('/{store}', [FavouriteStoreController::class, 'store'])->name('addFavourite');
        Route::delete('/{store}', [FavouriteStoreController::class, 'delete'])->name('removeFavourite');
        Route::get('/', [FavouriteStoreController::class, 'index'])->name('getFavourites');
    });

});



