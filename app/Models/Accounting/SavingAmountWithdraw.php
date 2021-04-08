<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingAmountWithdraw extends Model
{
    use HasFactory;

    protected $table = 'saving_amount_withdraws';
    public $incrementing = false;
    public $timestamps = true;

    public function member()
    {
        return $this->hasOne('App\Models\Members\Member', 'id', 'member_id');
    }
}
