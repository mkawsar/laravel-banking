<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api\Members',
    'prefix' => 'admin/member',
    'middleware' => ['auth:api', 'roles', 'token.active'],
    'roles' => ['admin', 'user', 'operator']
], function () {
    Route::get('list', 'MembersController@index');
    Route::post('create', 'MembersController@create');
    Route::get('{memberID}/details', 'MembersController@show');
    Route::post('{memberID}/update', 'MembersController@update');
    Route::get('search', 'MembersController@search');
    Route::delete('{memberID}/delete', 'MembersController@destroy');
});
