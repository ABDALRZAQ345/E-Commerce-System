<?php

use App\Http\Controllers\Product\FavouriteProductController;
use App\Http\Controllers\Store\FavouriteStoreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss','auth:sanctum'])->group(function () {

    Route::prefix('favourites/products')->name('products.')->group(function () {
        Route::post('/{product}', [FavouriteProductController::class, 'addFavourite'])->name('addFavourite');
        Route::delete('/{product}', [FavouriteProductController::class, 'removeFavourite'])->name('removeFavourite');
        Route::get('', [FavouriteProductController::class, 'getFavourites'])->name('getFavourites');
    });

    Route::prefix('favourites/stores')->name('stores.')->group(function () {
        Route::post('/{store}', [FavouriteStoreController::class, 'addFavourite'])->name('addFavourite');
        Route::delete('/{store}', [FavouriteStoreController::class, 'removeFavourite'])->name('removeFavourite');
        Route::get('/', [FavouriteStoreController::class, 'getFavourites'])->name('getFavourites');
    });

});
