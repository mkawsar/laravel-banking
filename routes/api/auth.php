<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api',
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
});
Route::group([
    'namespace' => 'Api',
    'prefix' => 'auth',
    'middleware' => ['auth:api', 'roles', 'token.active'],
    'roles' => ['admin']
], function () {
    Route::get('user/details', 'AuthController@details');
    Route::get('user/logout', 'AuthController@logout');
});
