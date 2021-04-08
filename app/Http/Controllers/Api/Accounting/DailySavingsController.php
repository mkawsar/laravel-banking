<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\DailySavings;
use App\Models\Accounting\MemberTotalSavingsAmount;
use App\Models\Accounting\SavingAmountWithdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class DailySavingsController extends Controller
{
    public function index()
    {
        return DailySavings::with('creator', 'member')
            ->whereDate('saving_date', '=', Carbon::today()->toDateString())
            ->orderBy('saving_date', 'desc')
            ->paginate(10);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required',
            'amount' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first(),
                'status' => 'validation'
            ]);
        }

        $todaySaving = DailySavings::where('member_id', '=', $request->member_id)
            ->whereDate('saving_date', '=', Carbon::today()->toDateString())
            ->first();

        if (!empty($todaySaving)) {
            return response([
                'message' => 'ইতিমধ্যে এই মেম্বারের দৈনিক সঞ্চয় যোগ করা হয়েছে।',
                'status' => 'failed'
            ]);
        }

        $dt = new \DateTime();
        $now = $dt->format('Y-m-d H:i:s');

        $uuid = Uuid::uuid4()->toString();

        $saving = new DailySavings();
        $saving->id = $uuid;
        $saving->saving_date = $now;
        $saving->creator_id = Auth::user()->id;
        $saving->member_id = $request->member_id;
        $saving->amount = $request->amount;

        if ($saving->save()) {
            $member = MemberTotalSavingsAmount::where('member_id', '=', $request->member_id)->first();
            if (empty($member)) {
                $totalSavings = new MemberTotalSavingsAmount();
                $totalSavings->id = Uuid::uuid4()->toString();
                $totalSavings->member_id = $request->member_id;
                $totalSavings->amount = $request->amount;
                $totalSavings->save();
            } else {
                $amount = $member->amount + $request->amount;
                MemberTotalSavingsAmount::where('id', '=', $member->id)->update(['amount' => $amount]);
            }
            return response([
                'message' => 'মেম্বারের দৈনিক সঞ্চয় যোগ করা হয়েছে।',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'মেম্বারের দৈনিক সঞ্চয় যোগ করা হয়নি',
                'status' => 'failed'
            ]);
        }

    }

    public function destroy($savingID)
    {
        if (DailySavings::where('id', '=', $savingID)->delete()) {
            return response(['message' => 'এই মেম্বার এর দৈনিক সঞ্চয় মুছে ফেলা হয়েছে।', 'status' => 'success']);
        } else {
            return response(['message' => 'এই মেম্বার এর দৈনিক সঞ্চয় মুছে ফেলা হয়নি।', 'status' => 'failed']);
        }
    }

    public function search(Request $request)
    {
        $savings = DailySavings::with('creator', 'member')
            ->where(function ($q) {
                $q->when(!empty(request('search')), function ($q) {
                    $q->whereHas('member', function ($q) {
                        return $q->where(DB::raw('lower(members.name)'), 'LIKE', '%' . strtolower(request('search')) . '%')
                            ->orWhere('members.member_id', 'LIKE', '%' . request('search') . '%');
                    });
                });
                $q->when(!empty(request('date')), function ($q) {
                    $q->whereDate('saving_date', '=', Carbon::parse(request('date'))->toDateString());
                });
            })
            ->orderBy('saving_date', 'desc')
            ->paginate(10);

        return $savings;
    }

    public function totalSavingsAmountList(Request $request)
    {
        return MemberTotalSavingsAmount::with('member.route')
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
    }

    public function memberListTotalSavings()
    {
        return MemberTotalSavingsAmount::with('member')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function withdrawSavingsAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required',
            'amount' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first(),
                'status' => 'validation'
            ]);
        }

        $savings = MemberTotalSavingsAmount::where('member_id', '=', $request->member_id)->first();
        if (empty($savings)) {
            return response([
                'message' => 'আপনার দেয়া সদস্যটি খুঁজে পাওয়া যায়নি',
                'status' => 'failed'
            ]);
        }
        if ($request->amount > $savings->amount) {
            return response([
                'message' => 'আপনি সঞ্চিত অৰ্থ এর সমপরিমাণ বা তার থেকে কম অর্থ উত্তোলন করতে পারবেন।',
                'status' => 'failed'
            ]);
        }
        $dt = new \DateTime();
        $now = $dt->format('Y-m-d H:i:s');

        $withdraw = new SavingAmountWithdraw();
        $withdraw->id = Uuid::uuid4()->toString();
        $withdraw->total_savings_id = $savings->id;
        $withdraw->member_id = $savings->member_id;
        $withdraw->withdraw_amount = $request->amount;
        $withdraw->withdraw_date = $now;
        $withdraw->creator_id = Auth::user()->id;

        if ($withdraw->save()) {
            $amount = $savings->amount - $request->amount;
            MemberTotalSavingsAmount::where('id', '=', $savings->id)->update(['amount' => $amount]);
            return response([
                'message' => 'আপনার সঞ্চিত অর্থ উত্তোলন সম্পূর্ণ হয়েছে',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'আপনার সঞ্চিত অর্থ উত্তোলন সম্পূর্ণ হয়নি',
                'status' => 'failed'
            ]);
        }
    }
}
