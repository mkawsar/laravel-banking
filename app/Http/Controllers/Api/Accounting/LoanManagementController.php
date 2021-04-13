<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MemberLoan;
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
            ->where(function($q) {
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
}
