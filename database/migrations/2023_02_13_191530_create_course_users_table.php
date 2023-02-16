<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('role_id')->default('1');
            $table->timestamps();

            //Relationships
            $table->index('user_id', 'course_user_user_idx');
            $table->index('course_id', 'course_user_course_idx');
            $table->index('role_id', 'course_user_role_idx');

            $table->foreign('user_id', 'course_user_user_fk')->on('users')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('course_id', 'course_user_course_fk')->on('courses')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id', 'course_user_role_fk')->on('user_roles')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_users');
    }
};
