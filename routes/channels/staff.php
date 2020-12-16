<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => []], function () {
    Route::group(['namespace' => 'Api'], function () {
        Route::post('staff/sign-in', 'StaffController@SignIn');
        Route::post('staff/valid-at', 'StaffController@ValidAt');
    });
});