<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('decision_id')->nullable();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('original_name');
            $table->string('folder_path')->unique();
            $table->timestamps();

            // Relationships
            $table->index('task_id', 'folder_task_idx');
            $table->index('decision_id', 'folder_decision_idx');
            $table->index('folder_id', 'folder_folder_idx');
            $table->index('user_id', 'folder_user_idx');

            $table->foreign('task_id', 'folder_task_fk')->on('tasks')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('decision_id', 'folder_decision_fk')->on('decisions')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('folder_id', 'folder_folder_fk')->on('folders')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id', 'folder_user_fk')->on('users')->references('id');
        });

        DB::table('folders')->insert([
            [
                'task_id' => 1,
                'decision_id' => null,
                'user_id' => 2,
                'original_name' => 'files',
                'folder_path' => '/storage/courses/course_1/task_1/files',
            ],
            [
                'task_id' => 2,
                'decision_id' => null,
                'user_id' => 2,
                'original_name' => 'files',
                'folder_path' => '/storage/courses/course_1/task_2/files',
            ],
            [
                'task_id' => 1,
                'decision_id' => 1,
                'user_id' => 3,
                'original_name' => 'user_3',
                'folder_path' => '/storage/courses/course_1/task_1/users/user_3',
            ],
            [
                'task_id' => 1,
                'decision_id' => 2,
                'user_id' => 4,
                'original_name' => 'user_4',
                'folder_path' => '/storage/courses/course_1/task_1/users/user_4',
            ],
        ]);

        if (!Storage::disk('public')->exists('courses/course_1/task_1/files')) {
            Storage::disk('public')->makeDirectory('courses/course_1/task_1/files');
        }

        if (!Storage::disk('public')->exists('courses/course_1/task_2/files')) {
            Storage::disk('public')->makeDirectory('courses/course_1/task_2/files');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folders');
    }
};
