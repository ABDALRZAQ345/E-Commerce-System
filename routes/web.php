<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'xss'], function () {

    Route::get('/csrf-token', function () {
        return response()->json([
            'csrfToken' => csrf_token(),
        ]);
    });

    Route::get('/', function () {});

});
