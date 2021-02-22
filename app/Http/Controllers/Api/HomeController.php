<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Members\Member;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $members = Member::count();

        return response()->json([
            'status' => 'success',
            'members' => $members
        ]);
    }
}
