<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api',
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
});
