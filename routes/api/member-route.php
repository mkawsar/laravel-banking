<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api\Members',
    'prefix' => 'admin/member/route',
    'middleware' => ['auth:api', 'roles', 'token.active'],
    'roles' => ['admin', 'user', 'operator']
], function () {
    Route::get('/', 'MemberRouteController@index');
    Route::get('list', 'MemberRouteController@list');
    Route::post('create', 'MemberRouteController@create');
    Route::delete('{routeId}/delete', 'MemberRouteController@destroy');
});
