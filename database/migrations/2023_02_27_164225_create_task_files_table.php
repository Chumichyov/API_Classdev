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
        Schema::create('task_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('file_extension_id');
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();

            // Relationships
            $table->index('task_id', 'task_file_task_idx');
            $table->index('user_id', 'task_file_user_idx');
            $table->index('file_extension_id', 'task_file_file_extension_idx');

            $table->foreign('task_id', 'task_file_task_fk')->on('tasks')->references('id');
            $table->foreign('user_id', 'task_file_user_fk')->on('users')->references('id');
            $table->foreign('file_extension_id', 'task_file_file_extension_fk')->on('file_extensions')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_files');
    }
};
