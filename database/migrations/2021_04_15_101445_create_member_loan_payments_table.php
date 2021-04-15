<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberLoanPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_loan_payments', function (Blueprint $table) {
            $table->string('id', '36')->primary();
            $table->string('loan_id');
            $table->string('member_id', '36');
            $table->string('installment_count');
            $table->string('payment_amount');
            $table->dateTime('payment_date');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->onDelete('cascade');
            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('loan_id')
                ->references('id')
                ->on('member_loans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_loan_payments');
    }
}
