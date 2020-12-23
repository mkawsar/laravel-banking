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
            ->where('role_id', '!=', 1)
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

    public function search(Request $request)
    {
        $queryString = strtolower($request->search);
        $users = User::with('role')
            ->whereRaw('lower(name) like (?)', ["%{$queryString}%"])
            ->where('role_id', '!=', 1)
            ->paginate(10);
        return $users;
    }

    public function details($userID)
    {
        return User::with('role')->find($userID);
    }

    public function update(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'role_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => 'validation']);
        }

        $payload = [
            'name' => $request->name,
            'role_id' => $request->role_id
        ];

        if (User::where('id', '=', $userId)->update($payload)) {
            return response([
                'message' => 'User edited successfully by admin.',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'Failed to edit user by admin.',
                'status' => 'failed'
            ]);
        }
    }

    public function destroy($userId)
    {
        if (User::where('id', '=', $userId)->delete()) {
            return response(['message' => 'User deleted successfully by admin.', 'status' => 'success']);
        } else {
            return response(['message' => 'Failed to delete user by admin.', 'status' => 'failed']);
        }
    }
}
