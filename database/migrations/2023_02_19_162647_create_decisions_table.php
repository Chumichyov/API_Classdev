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
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->text('description');
            $table->unsignedBigInteger('grade_id');
            $table->timestamps();

            // Relationships

            $table->index('user_id', 'decision_user_idx');
            $table->index('task_id', 'decision_task_idx');
            $table->index('grade_id', 'decision_grade_idx');

            $table->foreign('user_id', 'decision_user_fk')->on('users')->references('id');
            $table->foreign('task_id', 'decision_task_fk')->on('tasks')->references('id');
            $table->foreign('grade_id', 'decision_grade_fk')->on('grades')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decisions');
    }
};
