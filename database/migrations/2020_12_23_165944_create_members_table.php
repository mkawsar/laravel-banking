<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::create('members', function (Blueprint $table) {
            $table->string('id', '36')->primary();
            $table->string('member_id', '30')->unique();
            $table->string('name', '255')->nullable();
            $table->string('father_name', '255')->nullable();
            $table->string('mother_name', '255')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('nid', '255')->nullable();
            $table->string('phone', '255')->nullable();
            $table->string('nationality', '255')->nullable();
            $table->string('dob', '255')->nullable();
            $table->string('education_qualification', '255')->nullable();
            $table->string('nominee_name', '255')->nullable();
            $table->string('nominee_relation', '255')->nullable();
            $table->string('nominee_father_name', '255')->nullable();
            $table->string('nominee_mother_name', '255')->nullable();
            $table->text('nominee_address')->nullable();
            $table->string('picture', '255')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->string('member_route_id', '36')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('members');
        Schema::enableForeignKeyConstraints();
    }
}
