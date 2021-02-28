<?php

namespace App\Http\Controllers\Api\Members;

use App\Exports\MemberExport;
use App\Http\Controllers\Controller;
use App\Models\Members\Member;
use App\Models\Members\MemberRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;

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
        return Excel::download(new MemberExport($routeID), 'test.xls');
    }
}
