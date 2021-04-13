<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberInterestSetting extends Model
{
    use HasFactory;

    protected $table = 'member_interest_settings';
    public $incrementing = false;
    public $timestamps = true;
}
