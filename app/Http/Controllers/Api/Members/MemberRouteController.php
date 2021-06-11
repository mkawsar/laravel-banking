<?php

namespace App\Http\Controllers\Api\Members;

use App\Http\Controllers\Controller;
use App\Models\Members\Member;
use App\Models\Members\MemberRoute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use PDF;

class MemberRouteController extends Controller
{
    public function index()
    {
        return MemberRoute::with('creator')->get();
    }

    public function list()
    {
        return MemberRoute::with('creator')->paginate(10);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        $uuid = Uuid::uuid4()->toString();

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first(),
                'status' => 'validation'
            ]);
        }

        $route = new MemberRoute();
        $route->id = $uuid;
        $route->name = $request->name;
        $route->creator_id = Auth::user()->id;

        if ($route->save()) {
            return response([
                'message' => 'Member route information saved is successfully.',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'Failed to member route information save.',
                'status' => 'failed'
            ]);
        }
    }

    public function destroy($routeID)
    {
        $member = Member::where('member_route_id', '=', $routeID)->first();
        if (!empty($member)) {
            return response([
                'message' => 'এই রুট এ আপনার মেম্বার রেজিস্টার আছে, এই রুটের মেম্বার ডিলিট করুন/এডিট করুন, তারপরে রুট ডিলিট করুন',
                'status' => 'failed'
            ]);
        }

        if (MemberRoute::where('id', '=', $routeID)->delete()) {
            return response([
                'message' => 'এই রুটটি সুকসেসফুল্লি ডিলিট হয়েছে',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'এই রুটটি কোনো কারণে ডিলিট হয়নাই',
                'status' => 'failed'
            ]);
        }
    }

    public function details($routeID)
    {
        $members = Member::with('creator', 'route')
            ->where('member_route_id', '=', $routeID)
            ->paginate(10);

        foreach ($members as $member) {
            if ($member->picture != null) {
                $member->picture = config('constant.app.url') . 'images/members/thumb/thumb_200x200_' . $member->picture;
            }
        }

        return $members;
    }

    public function search(Request $request, $routeID)
    {
        $query = strtolower($request->search);

        $members = Member::with('creator', 'route')
            ->where('member_route_id', '=', $routeID)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('member_id', 'LIKE', '%' . $query . '%')
            ->paginate(10);
        foreach ($members as $member) {
            if ($member->picture != null) {
                $member->picture = config('constant.app.url') . 'images/members/thumb/thumb_200x200_' . $member->picture;
            }
        }
        return $members;
    }

    public function download($routeID)
    {
        $route = MemberRoute::find($routeID);
        $name = $route->name . '-' . Uuid::uuid4()->toString() . '.pdf';

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

        $members = Member::where('member_route_id', '=', $routeID)->get();

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


        $html .= '<body><h4 align="center" style="font-size: 18px;">' . $route->name . ' এর মেম্বার লিস্ট</h4>';
        $html .= '<table class="table" align="center">';
        $html .= '
                <tr class="row firstCell">
                    <th class="smallCell firstCell">মেম্বার নম্বর</th>
                    <th class="smallCell firstCell">নাম</th>
                    <th class="smallCell firstCell">মোবাইল নম্বর</th>
                    <th class="smallCell firstCell">ঠিকানা</th>
                    <th class="smallCell firstCell">তারিখ</th>
                    <th class="smallCell firstCell">জমা</th>
                </tr>';
        foreach ($members as $member) {
            $html .= '
                <tr class="row firstCell">
                    <td class="smallCell firstCell" align="center">' . $member->member_id . '</td>
                    <td class="smallCell firstCell" align="center">' . $member->name . '</td>
                    <td class="smallCell firstCell" align="center">' . $member->phone . '</td>
                    <td class="smallCell firstCell" align="center">' . $member->present_address . '</td>
                    <td class="smallCell firstCell" align="center">' . Carbon::today()->format('Y/m/d') . '</td>
                    <td class="smallCell firstCell" align="center"></td>
                </tr>';
        }
        $html .= '</table>';
        $html .= '</body></html>';

        $mpdf->WriteHTML($html);
//        $mpdf->Output();
        $mpdf->Output($name, 'D');
    }
}
