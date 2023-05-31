<?php

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
        Schema::create('messengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('student_id');
            $table->timestamps();

            // Relationships
            $table->index('course_id', 'messenger_course_idx');
            $table->index('teacher_id', 'messenger_teacher_idx');
            $table->index('student_id', 'messenger_student_idx');

            $table->foreign('course_id', 'messenger_course_fk')->on('courses')->references('id');
            $table->foreign('teacher_id', 'messenger_teacher_fk')->on('users')->references('id');
            $table->foreign('student_id', 'messenger_student_fk')->on('users')->references('id');
        });

        DB::table('messengers')->insert([
            [
                'course_id' => 1,
                'teacher_id' => 2,
                'student_id' => 3,
            ],
            [
                'course_id' => 1,
                'teacher_id' => 2,
                'student_id' => 4,
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
        Schema::dropIfExists('messengers');
    }
};
