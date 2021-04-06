<?php

use \Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api\Accounting',
    'prefix' => 'admin/accounting',
    'middleware' => ['auth:api', 'roles', 'token.active'],
    'roles' => ['admin', 'account']
], function () {
    Route::get('daily/saving/list', 'DailySavingsController@index');
    Route::post('daily/saving/add', 'DailySavingsController@store');
});
