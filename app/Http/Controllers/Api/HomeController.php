<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Accounting\DailySavings;
use App\Models\Members\Member;
use App\Models\Members\MemberRoute;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $members = Member::count();
        $routes = MemberRoute::count();
        $savings = DailySavings::sum('amount');

        return response()->json([
            'status' => 'success',
            'members' => $members,
            'routes' => $routes,
            'savings' => $savings
        ]);
    }
}
