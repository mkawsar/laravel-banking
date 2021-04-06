<?php

use \Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api\Accounting',
    'prefix' => 'admin/accounting',
    'middleware' => ['auth:api', 'roles', 'token.active'],
    'roles' => ['admin', 'account']
], function () {
    Route::get('daily/saving/list', 'DailySavingsController@index');
    Route::get('daily/saving/list/search', 'DailySavingsController@search');
    Route::post('daily/saving/add', 'DailySavingsController@store');
    Route::delete('daily/saving/{savingID}/delete', 'DailySavingsController@destroy');
});
