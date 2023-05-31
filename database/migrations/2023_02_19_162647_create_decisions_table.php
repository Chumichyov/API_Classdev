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
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->text('description')->nullable();
            $table->string('grade')->nullable();
            $table->unsignedBigInteger('completed_id')->default(1);
            $table->timestamps();

            // Relationships

            $table->index('user_id', 'decision_user_idx');
            $table->index('task_id', 'decision_task_idx');
            $table->index('completed_id', 'decision_completed_idx');

            $table->foreign('user_id', 'decision_user_fk')->on('users')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('task_id', 'decision_task_fk')->on('tasks')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('completed_id', 'decision_completed_fk')->on('decision_completed')->references('id');
        });

        DB::table('decisions')->insert([
            [
                'user_id' => 3,
                'task_id' => 1,
            ],
            [
                'user_id' => 4,
                'task_id' => 1,
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
        Schema::dropIfExists('decisions');
    }
};
