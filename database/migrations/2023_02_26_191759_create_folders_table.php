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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('decision_id')->nullable();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->string('folder_path')->unique();
            $table->timestamps();

            // Relationships
            $table->index('task_id', 'folder_task_idx');
            $table->index('decision_id', 'folder_decision_idx');
            $table->index('folder_id', 'folder_folder_idx');

            $table->foreign('task_id', 'folder_task_fk')->on('tasks')->references('id');
            $table->foreign('decision_id', 'folder_decision_fk')->on('decisions')->references('id');
            $table->foreign('folder_id', 'folder_folder_fk')->on('folders')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
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
