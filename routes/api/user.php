<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api\User',
    'prefix' => 'admin/user',
    'middleware' => ['auth:api', 'roles', 'token.active'],
    'roles' => ['admin']
], function () {
    Route::get('list', 'UserController@index');
    Route::post('create', 'UserController@create');
    Route::get('search', 'UserController@search');
    Route::get('{userID}/details', 'UserController@details');
    Route::post('{userID}/update', 'UserController@update');
    Route::delete('{userID}/delete', 'UserController@destroy');
});
