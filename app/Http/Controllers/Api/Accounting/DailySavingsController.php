<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\DailySavings;
use App\Models\Accounting\MemberTotalSavingsAmount;
use App\Models\Accounting\SavingAmountWithdraw;
use App\Models\Members\Member;
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
        return DailySavings::with('creator', 'member.route')
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
        $savings = DailySavings::with('creator', 'member.route')
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

    public function memberSavingsDetails($memberID)
    {
        $savingsAmount = DailySavings::where('member_id', '=', $memberID)->sum('amount');
        $withdrawAmount = SavingAmountWithdraw::where('member_id', '=', $memberID)->sum('withdraw_amount');
        $remainAmount = $savingsAmount - $withdrawAmount;
        return response([
            'savings_amount' => $savingsAmount,
            'withdraw_amount' => $withdrawAmount,
            'remain_amount' => $remainAmount,
        ]);
    }

    public function memberDailySavingsList(Request $request, $memberID)
    {
        return DailySavings::with('member.route')
            ->where('member_id', '=', $memberID)
            ->where(function ($q) {
                $q->when(!empty(request('search')), function ($q) {
                    $q->whereDate('saving_date', '=', Carbon::parse(request('search'))->toDateString());
                });
            })
            ->orderBy('saving_date', 'desc')
            ->paginate(10);
    }

    public function memberDailySavingsDownload($memberID)
    {
        $member = Member::select('name')->where('id', '=', $memberID)->first();
        $name = str_replace(' ', '_', $member->name) . '-মোট সঞ্চয়-' . Uuid::uuid4()->toString() . '.pdf';
        $amounts = DailySavings::with('member.route')
            ->where('member_id', '=', $memberID)
            ->orderBy('saving_date', 'desc')
            ->get();

        $savingsAmount = DailySavings::where('member_id', '=', $memberID)->sum('amount');
        $withdrawAmount = SavingAmountWithdraw::where('member_id', '=', $memberID)->sum('withdraw_amount');
        $remainAmount = $savingsAmount - $withdrawAmount;

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path() . '/assets/fonts',
            ]),
            'fontdata' => $fontData + [
                    'kalpurush' => [
                        'R' => 'kalpurush.ttf', // regular font
                        'B' => 'kalpurush.ttf', // optional: bold font
                        'I' => 'kalpurush.ttf', // optional: italic font
                        'BI' => 'kalpurush.ttf', // optional: bold-italic font
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ]
                ],
            'default_font' => 'kalpurush'
        ]);

        $html = '';
        $html .= '<head><style>
                        .table{
                            border-left: thin solid;
                            border-right: thin solid;
                            border-bottom: thin solid #000000;
                            border-top: thin solid;
                        }

                        .table{
                            margin-top: 10px;
                            margin-bottom: 10px;
                            border-collapse: collapse;
                        }

                        table tr {
                            border-bottom: 1px solid black;
                        }

                        table tr:last-child {
                            border-bottom: none;
                        }

                        .Cell
                        {

                            width: 300px;
                        }

                        .firstCell
                        {
                            border-left: thin;
                            border-right: thin solid;
                            border-bottom: thin;
                            border-top: thin;
                        }

                        .smallCell
                        {
                            width:150px;
                        }

                        .largeCell
                        {
                            width: 450px;
                        }

                        .row
                        {
                            display: block;
                        }

                        .koninklijkeLogo
                        {
                            width: 30px;
                        }

                        .maxSize
                        {
                            width: 600px;
                        }
                </style></head>';


        $html .= '<body><h4 align="center" style="font-size: 18px;">' . $member->name . ' মোট সঞ্চয় তালিকা</h4>';
        $html .= '<table class="table" align="center">';
        $html .= '
                <tr class="row firstCell">
                    <th class="smallCell firstCell">মোট সঞ্চয়</th>
                    <th class="smallCell firstCell">মোট উত্তোলন</th>
                    <th class="smallCell firstCell">অবশিষ্ট সঞ্চয়</th>
                </tr>';
        $html .= '
                <tr class="row firstCell">
                    <td class="smallCell firstCell" align="center">' . $savingsAmount . ' TK' . '</td>
                    <td class="smallCell firstCell" align="center">' . $withdrawAmount . ' TK' . '</td>
                    <td class="smallCell firstCell" align="center">' . $remainAmount . ' TK' . '</td>
                </tr>';
        $html .= '</table>';

        $html .= '<table class="table" align="center">';
        $html .= '
                <tr class="row firstCell">
                    <th class="smallCell firstCell">নাম</th>
                    <th class="smallCell firstCell">মেম্বার নম্বর</th>
                    <th class="smallCell firstCell">রুট নাম</th>
                    <th class="smallCell firstCell">জমা টাকা</th>
                    <th class="smallCell firstCell">জমাদানের তারিখ</th>
                </tr>';
        foreach ($amounts as $data) {
            $html .= '
                <tr class="row firstCell">
                    <td class="smallCell firstCell" align="center">' . $data->member->name . '</td>
                    <td class="smallCell firstCell" align="center">' . $data->member->member_id . '</td>
                    <td class="smallCell firstCell" align="center">' . $data->member->route->name . '</td>
                    <td class="smallCell firstCell" align="center">' . $data->amount . ' TK' . '</td>
                    <td class="smallCell firstCell" align="center">' . Carbon::parse($data->saving_date)->format('Y/m/d H:m:i') . '</td>
                </tr>';
        }
        $html .= '</table>';
        $html .= '</body></html>';

        $mpdf->WriteHTML($html);
//        $mpdf->Output();
        $mpdf->Output($name, 'D');
    }


    public function memberSavingsWithdrawList(Request $request, $memberID)
    {
        return SavingAmountWithdraw::with('member.route')
            ->where('member_id', '=', $memberID)
            ->where(function ($q) {
                $q->when(!empty(request('search')), function ($q) {
                    $q->whereMonth('withdraw_date', '=', Carbon::parse(request('search'))->toDateString());
                });
            })
            ->orderBy('withdraw_date', 'desc')
            ->paginate(10);
    }
}
