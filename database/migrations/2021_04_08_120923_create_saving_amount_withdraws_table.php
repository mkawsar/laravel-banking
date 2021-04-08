<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingAmountWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_amount_withdraws', function (Blueprint $table) {
            $table->string('id', '36')->primary();
            $table->string('total_savings_id', '36');
            $table->string('member_id', '36');
            $table->string('withdraw_amount');
            $table->dateTime('withdraw_date');
            $table->integer('creator_id');
            $table->timestamps();

            $table->foreign('total_savings_id')->references('id')->on('member_total_savings_amounts')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saving_amount_withdraws');
    }
}
