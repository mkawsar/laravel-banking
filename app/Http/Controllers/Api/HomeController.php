<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MemberLoan;
use App\Models\Accounting\MemberLoanPayment;
use App\Models\Accounting\MemberTotalSavingsAmount;
use App\Models\Members\Member;
use App\Models\Members\MemberRoute;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $members = Member::count();
        $routes = MemberRoute::count();
        $savings = floor(MemberTotalSavingsAmount::sum('amount'));
        $totalLoan = floor(MemberLoan::sum('loan_amount'));
        $totalLoanPayment = floor(MemberLoanPayment::sum('payment_amount'));
        $yearlyLoan = floor(MemberLoan::whereYear('created_at', $today)->sum('loan_amount'));
        $yearlyLoanInterest = floor(MemberLoan::whereYear('created_at', $today)->sum('interest_amount'));
        $monthlyLoan = floor(MemberLoan::whereMonth('created_at', $today)->sum('loan_amount'));
        $monthlyLoanInterest = floor(MemberLoan::whereMonth('created_at', $today)->sum('interest_amount'));
        $monthlyLoanPayment = floor(MemberLoanPayment::whereMonth('payment_date', $today)->sum('payment_amount'));

        $remainingLoanAmount = floor($totalLoan - $totalLoanPayment);

        return response()->json([
            'status' => 'success',
            'members' => $members,
            'routes' => $routes,
            'savings' => round($savings, 2),
            'monthly_loan_amount' => $monthlyLoan,
            'monthly_loan_interest_amount' => $monthlyLoanInterest,
            'monthly_loan_payment_amount' => $monthlyLoanPayment,
            'yearly_loan_amount' => $yearlyLoan,
            'yearly_loan_interest_amount' => $yearlyLoanInterest,
            'total_remaining_loan' => $remainingLoanAmount
        ]);
    }
}
