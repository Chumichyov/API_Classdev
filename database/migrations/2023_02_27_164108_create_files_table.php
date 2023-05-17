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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('decision_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->unsignedBigInteger('file_extension_id')->nullable();
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();

            // Relationships
            $table->index('task_id', 'file_task_idx');
            $table->index('decision_id', 'file_decision_idx');
            $table->index('user_id', 'file_user_idx');
            $table->index('folder_id', 'file_folder_idx');
            $table->index('file_extension_id', 'file_file_extension_idx');

            $table->foreign('task_id', 'file_task_fk')->on('tasks')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('decision_id', 'file_decision_fk')->on('decisions')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id', 'file_user_fk')->on('users')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('folder_id', 'file_folder_fk')->on('folders')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('file_extension_id', 'file_file_extension_fk')->on('file_extensions')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decision_files');
    }
};
