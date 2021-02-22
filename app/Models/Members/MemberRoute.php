<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberRoute extends Model
{
    use SoftDeletes;

    protected $table = 'member_routes';
    public $incrementing = false;
    public $timestamps = true;

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'creator_id');
    }
}
