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

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'creator_id');
    }
}
