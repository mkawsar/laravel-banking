<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberLoanPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'member_loan_payments';
    public $incrementing = false;
    public $timestamps = true;

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'creator_id');
    }
    public function member()
    {
        return $this->hasOne('App\Models\Members\Member', 'id', 'member_id');
    }
}
