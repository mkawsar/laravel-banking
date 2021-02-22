<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $table = 'members';
    public $incrementing = false;
    public $timestamps = true;

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'creator_id');
    }

    public function route()
    {
        return $this->hasOne('App\Models\Members\MemberRoute', 'id', 'member_route_id');
    }
}
