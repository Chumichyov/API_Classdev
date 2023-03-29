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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('decision_id')->nullable();
            $table->string('message');
            $table->timestamps();

            //Relationships

            $table->index('user_id', 'notification_user_idx');
            $table->index('course_id', 'notification_course_idx');
            $table->index('task_id', 'notification_task_idx');
            $table->index('decision_id', 'notification_decision_idx');

            $table->foreign('user_id', 'notification_user_fk')->on('users')->references('id');
            $table->foreign('course_id', 'notification_course_fk')->on('courses')->references('id');
            $table->foreign('task_id', 'notification_task_fk')->on('tasks')->references('id');
            $table->foreign('decision_id', 'notification_decision_fk')->on('decisions')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
