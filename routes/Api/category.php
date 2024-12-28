<?php

use App\Http\Controllers\Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss','auth:sanctum'])->group(function () {

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

});
