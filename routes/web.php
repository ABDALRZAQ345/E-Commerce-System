<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'xss'],function (){

    Route::get('/csrf-token', function () {
        return response()->json([
            'csrfToken' => csrf_token(),
        ]);
    });



});
