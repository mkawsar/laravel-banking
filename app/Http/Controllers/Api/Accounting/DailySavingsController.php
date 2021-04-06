<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\DailySavings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class DailySavingsController extends Controller
{
    public function index()
    {
        return DailySavings::with('creator', 'member')->paginate(10);
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
}
