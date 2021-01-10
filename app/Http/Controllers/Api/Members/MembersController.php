<?php

namespace App\Http\Controllers\Api\Members;

use App\Http\Controllers\Controller;
use App\Models\Members\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class MembersController extends Controller
{
    public function index()
    {
        return Member::with('creator')->paginate(10);
    }

    public function create(Request $request)
    {
        $memberID = '00' . Carbon::now()->year . $request->member_id;
        $validationMember = Member::where('member_id', '=', $memberID)->first();
        if (!empty($validationMember)) {
            return response([
                'message' => 'This member already exist',
                'status' => 'validation'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'present_address' => 'required',
            'permanent_address' => 'required',
            'nid' => 'required',
            'phone' => 'required',
            'nominee_name' => 'required',
            'nominee_relation' => 'required',
            'nominee_father_name' => 'required',
            'nominee_mother_name' => 'required',
            'nominee_address' => 'required',
            'picture' => 'required'
        ]);

        $uuid = Uuid::uuid4()->toString();

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first(),
                'status' => 'validation'
            ]);
        }

        $imageName = '';

        if ($request->file('picture') != null) {
            $file = $request->file('picture');
            $imageName = $uuid . '-' . $file->getClientOriginalName();
            $thumb_200x200 = 'thumb_200x200_' . $imageName;

            $request->file('picture')->move(
                public_path() . '/images/members/', $imageName
            );
            $path = public_path() . '/images/members/' . $imageName;

            Image::make($path)->resize(200, 200)->save(public_path() . '/images/members/thumb/' . $thumb_200x200);
        }

        $member = new Member();
        $member->id = $uuid;
        $member->member_id = $memberID;
        $member->name = $request->name;
        $member->father_name = $request->father_name;
        $member->mother_name = $request->mother_name;
        $member->present_address = $request->present_address;
        $member->permanent_address = $request->permanent_address;
        $member->nid = $request->nid;
        $member->phone = $request->phone;
        $member->nationality = $request->nationality;
        $member->dob = $request->dob;
        $member->education_qualification = $request->education_qualification;
        $member->nominee_name = $request->nominee_name;
        $member->nominee_relation = $request->nominee_relation;
        $member->nominee_father_name = $request->nominee_father_name;
        $member->nominee_mother_name = $request->nominee_mother_name;
        $member->nominee_address = $request->nominee_address;
        $member->picture = $imageName;
        $member->creator_id = Auth::user()->id;

        if ($member->save()) {
            return response([
                'message' => 'New member information saved is successfully.',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'Failed to new member information save.',
                'status' => 'failed'
            ]);
        }
    }

    public function show($memberID)
    {
        return Member::find($memberID);
    }

    public function update(Request $request, $memberID)
    {
        $member = Member::find($memberID);
        if (empty($member)) {
            return response([
                'message' => 'Member not found',
                'status' => 'failed'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'present_address' => 'required',
            'permanent_address' => 'required',
            'nid' => 'required',
            'phone' => 'required',
            'nominee_name' => 'required',
            'nominee_relation' => 'required',
            'nominee_father_name' => 'required',
            'nominee_mother_name' => 'required',
            'nominee_address' => 'required',
            'picture' => 'required'
        ]);

        $uuid = Uuid::uuid4()->toString();

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first(),
                'status' => 'validation'
            ]);
        }

        $imageName = $member->picture;

        if ($request->file('picture') != null) {
            $file = $request->file('picture');
            $imageName = $uuid . '-' . $file->getClientOriginalName();
            $thumb_200x200 = 'thumb_200x200_' . $imageName;

            $request->file('picture')->move(
                public_path() . '/images/members/', $imageName
            );
            $path = public_path() . '/images/members/' . $imageName;

            Image::make($path)->resize(200, 200)->save(public_path() . '/images/members/thumb/' . $thumb_200x200);
            if (file_exists(public_path() . '/images/members/' . $member->picture) && $member->picture != null) {
                @unlink(public_path() . '/images/members/thumb/thumb_200x200_' . $member->picture);
                @unlink(public_path() . '/images/members/' . $member->picture);
            }
        }

        $payload = [
            'name' => $request->name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'present_address' => $request->present_address,
            'permanent_address' => $request->permanent_address,
            'nid' => $request->nid,
            'phone' => $request->phone,
            'nationality' => $request->nationality,
            'dob' => $request->dob,
            'education_qualification' => $request->education_qualification,
            'nominee_name' => $request->nominee_name,
            'nominee_relation' => $request->nominee_relation,
            'nominee_father_name' => $request->nominee_father_name,
            'nominee_mother_name' => $request->nominee_mother_name,
            'nominee_address' => $request->nominee_address,
            'picture' => $imageName,
        ];

        if (Member::where('id', '=',$memberID)->update($payload)) {
            return response([
                'message' => 'Member profile information updated successfully.',
                'status' => 'success'
            ]);
        } else {
            return response([
                'message' => 'Failed to update member profile information.',
                'status' => 'failed'
            ]);
        }
    }
}
