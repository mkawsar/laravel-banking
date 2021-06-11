<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberTotalSavingsAmount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'member_total_savings_amounts';
    public $incrementing = false;
    public $timestamps = true;

    public function member()
    {
        return $this->hasOne('App\Models\Members\Member', 'id', 'member_id');
    }
}
