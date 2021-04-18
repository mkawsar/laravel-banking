<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MemberLoan;
use App\Models\Accounting\MemberLoanPayment;
use App\Models\Settings\MemberInterestSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class LoanManagementController extends Controller
{
    public function index()
    {
        $loans = MemberLoan::with('member.route', 'creator')
            ->where(function ($q) {
                $q->when(!empty(request('search')), function ($q) {
                    $q->whereHas('member', function ($q) {
                        return $q->where(DB::raw('lower(members.name)'), 'LIKE', '%' . strtolower(request('search')) . '%')
                            ->orWhere('members.member_id', 'LIKE', '%' . request('search') . '%');
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        foreach ($loans as $loan) {
            $loan->installment = config('constant.loan.installment');
            $paidAmount = MemberLoanPayment::where('loan_id', '=', $loan->id)->sum('payment_amount');
            $paidInstallment = 0;

            $installment = MemberLoanPayment::where('loan_id', '=', $loan->id)->orderBy('created_at', 'desc')->first();
            if (!empty($installment)) {
                $paidInstallment = $installment->installment_count;
            }
            $dueAmount = $loan->loan_amount - $paidAmount;
            $dueInstallment = config('constant.loan.installment') - $paidInstallment;

            $loan->paid_amount = $paidAmount;
            $loan->due_amount = $dueAmount;
            $loan->paid_installment = $paidInstallment;
            $loan->due_installment = $dueInstallment;
        }

        return $loans;
    }

    public function storeMemberLoan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required',
            'loan_amount' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first(),
                'status' => 'validation'
            ]);
        }

        $setting = MemberInterestSetting::where('type', '=', 'loan')->first();
        $percentage = $setting->interest_amount;

        $loanAmount = $request->loan_amount;
        $interest = ($loanAmount * $percentage) / 100;
        $amount = $loanAmount + $interest;
        $perInstallment = $amount / config('constant.loan.installment');

        $loan = new MemberLoan();
        $loan->id = Uuid::uuid4()->toString();
        $loan->member_id = $request->member_id;
        $loan->loan_amount = $amount;
        $loan->interest_percentage = $percentage;
        $loan->interest_amount = $interest;
        $loan->installment_amount = $perInstallment;
        $loan->creator_id = Auth::user()->id;
        if ($loan->save()) {
            return response([
                'message' => 'মেম্বারের লোন যোগ করা হয়েছে।',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'মেম্বারের লোন যোগ করা হয়নি',
                'status' => 'failed'
            ]);
        }
    }

    public function destroy($loanID)
    {
        if (MemberLoan::where('id', '=', $loanID)->delete()) {
            return response(['message' => 'এই মেম্বার এর লোন মুছে ফেলা হয়েছে।', 'status' => 'success']);
        } else {
            return response(['message' => 'এই মেম্বার এর লোন মুছে ফেলা হয়নি।', 'status' => 'failed']);
        }
    }

    public function details($loanID)
    {
        $loan = MemberLoan::with('member')->find($loanID);
        $mainAmount = $loan->loan_amount;

        $paidAmount = MemberLoanPayment::where('loan_id', '=', $loanID)->sum('payment_amount');
        $paidInstallment = 0;

        $installment = MemberLoanPayment::where('loan_id', '=', $loanID)->orderBy('created_at', 'desc')->first();
        if (!empty($installment)) {
            $paidInstallment = $installment->installment_count;
        }
        $dueAmount = $mainAmount - $paidAmount;

        return response()->json([
            'loan' => $loan,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'due_installment' => config('constant.loan.installment') - $paidInstallment,
            'paid_installment' => $paidInstallment
        ], 200);
    }

    public function memberStoreLoanPayment(Request $request, $loanID)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first(),
                'status' => 'validation'
            ]);
        }

        $loan = MemberLoan::find($loanID);
        if ($loan->installment_amount !== $request->amount) {
            return response([
                'message' => 'আপনার আজকের কিস্তি ' . $loan->installment_amount . ' টাকা, কিন্তু আপনি এই টাকার কম টাকা দিয়েছেন, তাই সমপরিমান টাকা দিন',
                'status' => 'validation'
            ]);
        }

        $paymentCount = 1;

        $loanPayment = MemberLoanPayment::where('loan_id', '=', $loanID)->orderBy('created_at', 'desc')->first();

        if (!empty($loanPayment)) {
            $paymentCount = $loanPayment->installment_count + 1;

            if (config('constant.loan.installment') == $loanPayment->installment_count) {
                return response([
                    'message' => 'আপনার সম্পূর্ণ লোন পরিশোধ হয়েছে।',
                    'status' => 'success'
                ]);
            }
        }


        $dt = new \DateTime();
        $now = $dt->format('Y-m-d H:i:s');

        $payment = new MemberLoanPayment();
        $payment->id = Uuid::uuid4()->toString();
        $payment->loan_id = $loanID;
        $payment->member_id = $loan->member_id;
        $payment->installment_count = $paymentCount;
        $payment->payment_amount = $request->amount;
        $payment->payment_date = $now;
        $payment->creator_id = Auth::user()->id;

        if ($payment->save()) {
            return response([
                'message' => 'আপনার আজকের কিস্তি দেয়া হয়েছে।',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'আপনার আজকের কিস্তি দেয়া হয়নি',
                'status' => 'failed'
            ]);
        }
    }

    public function loanPaymentList($loanID, $memberID)
    {
        return MemberLoanPayment::with('member.route', 'creator')
            ->where('loan_id', '=', $loanID)
            ->where('member_id', '=', $memberID)
            ->orderBy('payment_date', 'desc')
            ->paginate(10);
    }
}
