<?php

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('decision_id')->nullable();
            $table->string('message');
            $table->timestamps();

            //Relationships

            $table->index('type_id', 'notification_type_idx');
            $table->index('recipient_id', 'notification_recipient_idx');
            $table->index('user_id', 'notification_user_idx');
            $table->index('course_id', 'notification_course_idx');
            $table->index('task_id', 'notification_task_idx');
            $table->index('decision_id', 'notification_decision_idx');

            $table->foreign('type_id', 'notification_type_fk')->on('notification_types')->references('id');
            $table->foreign('recipient_id', 'notification_recipient_fk')->on('users')->references('id');
            $table->foreign('user_id', 'notification_user_fk')->on('users')->references('id');
            $table->foreign('course_id', 'notification_course_fk')->on('courses')->references('id');
            $table->foreign('task_id', 'notification_task_fk')->on('tasks')->references('id');
            $table->foreign('decision_id', 'notification_decision_fk')->on('decisions')->references('id');
        });

        DB::table('notifications')->insert([
            [
                'type_id' => 2,
                'recipient_id' => 2,
                'user_id' => 3,
                'course_id' => 1,
                'message' => "В курсе '" . Course::find(1)->title . "' появился новый участник: " . User::find(3)->name . ' ' . User::find(3)->surname,
            ],
            [
                'type_id' => 2,
                'recipient_id' => 2,
                'user_id' => 4,
                'course_id' => 1,
                'message' => "В курсе '" . Course::find(1)->title . "' появился новый участник: " . User::find(4)->name . ' ' . User::find(4)->surname,
            ],
            [
                'type_id' => 3,
                'recipient_id' => 3,
                'course_id' => 1,
                'task_id' => 1,
                'message' => "В курсе '" . Course::find(1)->title . "' выложено новое задание: " . Task::find(1)->title,
            ],
            [
                'type_id' => 3,
                'recipient_id' => 3,
                'course_id' => 1,
                'task_id' => 2,
                'message' => "В курсе '" . Course::find(1)->title . "' выложено новое задание: " . Task::find(1)->title,
            ],
            [
                'type_id' => 3,
                'recipient_id' => 4,
                'course_id' => 1,
                'task_id' => 1,
                'message' => "В курсе '" . Course::find(1)->title . "' выложено новое задание: " . Task::find(1)->title,
            ],
            [
                'type_id' => 3,
                'recipient_id' => 4,
                'course_id' => 1,
                'task_id' => 2,
                'message' => "В курсе '" . Course::find(1)->title . "' выложено новое задание: " . Task::find(1)->title,
            ],
        ]);
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
