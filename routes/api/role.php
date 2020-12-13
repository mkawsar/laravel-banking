<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api\User',
    'prefix' => 'admin',
    'middleware' => ['auth:api', 'roles', 'token.active'],
    'roles' => ['admin']
], function () {
    Route::get('all/role', 'RoleController@index');
});
