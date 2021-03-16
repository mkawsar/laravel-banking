<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', 'AppController@index')->where('any', '.*');

//Route::get('/storage', function () {
//    \Illuminate\Support\Facades\Artisan::call('storage:link');
//});
