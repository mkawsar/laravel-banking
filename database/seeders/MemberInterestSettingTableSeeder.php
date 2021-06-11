<?php

namespace Database\Seeders;

use App\Models\Settings\MemberInterestSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class MemberInterestSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('member_interest_settings')->truncate();

        $loan = new MemberInterestSetting();
        $loan->id = Uuid::uuid4()->toString();
        $loan->type = 'loan';
        $loan->interest_amount = '13.60';
        $loan->save();

        $savings = new MemberInterestSetting();
        $savings->id = Uuid::uuid4()->toString();
        $savings->type = 'savings';
        $savings->interest_amount = '5';
        $savings->save();
    }
}
