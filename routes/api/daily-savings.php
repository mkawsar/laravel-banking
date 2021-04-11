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


    Route::get('total/saving/list', 'DailySavingsController@totalSavingsAmountList');
    Route::get('total/saving/member/list', 'DailySavingsController@memberListTotalSavings');
    Route::post('total/saving/withdraw', 'DailySavingsController@withdrawSavingsAmount');

    Route::get('member/{memberID}/savings/details', 'DailySavingsController@memberSavingsDetails');
    Route::get('member/{memberID}/daily/savings/list', 'DailySavingsController@memberDailySavingsList');
    Route::get('member/{memberID}/savings/withdraw/list', 'DailySavingsController@memberSavingsWithdrawList');

    Route::get('member/{memberID}/daily/savings/download', 'DailySavingsController@memberDailySavingsDownload');
});

Route::group([
    'namespace' => 'Api\Accounting',
    'prefix' => 'admin/accounting'
], function () {
    Route::get('member/{memberID}/daily/savings/download', 'DailySavingsController@memberDailySavingsDownload');
    Route::get('member/{memberID}/savings/withdraw/history/download', 'DailySavingsController@memberSavingsWithdrawDownload');
});
