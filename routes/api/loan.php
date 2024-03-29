<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api\Accounting',
    'prefix' => 'admin/accounting/loan',
    'middleware' => ['auth:api', 'roles', 'token.active'],
    'roles' => ['admin', 'account', 'user']
], function () {
    Route::get('member/list', 'LoanManagementController@index');
    Route::post('member/add', 'LoanManagementController@storeMemberLoan');
    Route::delete('{loanID}/member/delete', 'LoanManagementController@destroy');
    Route::get('{loanID}/details', 'LoanManagementController@details');
    Route::post('{loanID}/store/payment', 'LoanManagementController@memberStoreLoanPayment');
    Route::get('{loanID}/{memberID}/payment/list', 'LoanManagementController@loanPaymentList');
});
