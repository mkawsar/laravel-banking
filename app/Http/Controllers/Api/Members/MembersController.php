<?php

namespace App\Http\Controllers\Api\Members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class MembersController extends Controller
{
    public function index()
    {
        return 'test';
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'present_address' => 'required',
            'permanent_address' => 'required',
            'nid' => 'required',
            'phone' => 'required',
            'nominee_name' => 'required',
            'nominee_relation' => 'required',
            'nominee_father_name' => 'required',
            'nominee_address' => 'required',
            'picture' => 'required'
        ]);

        $uuid = Uuid::uuid4()->toString();

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'type' => 'validation'], 400);
        }

        if ($request->file('picture') != null) {
            $file = $request->file('picture');
            $imageName = $uuid . '-' . $file->getClientOriginalName();
            $thumb_96x96 = 'thumb_96x96_' . $imageName;

            $request->file('picture')->move(
                public_path() . '/images/profile/', $imageName
            );
            $path = public_path() . '/images/profile/' . $imageName;

            Image::make($path)->resize(96, 96)->save(public_path() . '/images/members/thumb/' . $thumb_96x96);
        }
    }
}
