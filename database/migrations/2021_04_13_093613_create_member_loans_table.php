<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_loans', function (Blueprint $table) {
            $table->string('id', '36')->primary();
            $table->string('member_id', '36');
            $table->string('loan_amount')->nullable();
            $table->string('interest_percentage')->nullable();
            $table->string('interest_amount')->nullable();
            $table->string('installment_amount')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->boolean('completed_status')->default(0);
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_loans');
    }
}
