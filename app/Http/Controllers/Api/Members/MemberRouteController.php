<?php

namespace App\Http\Controllers\Api\Members;

use App\Http\Controllers\Controller;
use App\Models\Members\MemberRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
}
