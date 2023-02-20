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
            $table->unsignedBigInteger('file_type_id');
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('decision_id')->nullable();
            $table->unsignedBigInteger('file_extension_id');
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();

            // Relationships
            $table->index('file_type_id', 'file_file_type_idx');
            $table->index('task_id', 'file_task_idx');
            $table->index('decision_id', 'file_decision_idx');
            $table->index('file_extension_id', 'file_file_extension_idx');

            $table->foreign('file_type_id', 'file_file_type_fk')->on('file_types')->references('id');
            $table->foreign('task_id', 'file_task_fk')->on('tasks')->references('id');
            $table->foreign('decision_id', 'file_decision_fk')->on('decisions')->references('id');
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
        Schema::dropIfExists('files');
    }
};
