<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api',
    'prefix' => 'admin/home',
    'middleware' => ['auth:api', 'roles', 'token.active'],
    'roles' => ['admin', 'account', 'user']
], function () {
    Route::get('details', 'HomeController@index');
});
