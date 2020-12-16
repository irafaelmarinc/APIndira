<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => []], function () {
    Route::group(['namespace' => 'Api'], function () {
    });
});
