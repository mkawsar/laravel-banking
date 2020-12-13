<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return User::with('role')
            ->where('role_id', '=', 1)
            ->paginate(10);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'role_id' => 'required',
            'email' => 'required|email|Between:3,64|unique:users'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => 'validation']);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id;

        if ($user->save()) {
            return response([
                'message' => 'User created successfully by admin.',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'Failed to create user by admin.',
                'status' => 'failed'
            ]);
        }
    }
}
