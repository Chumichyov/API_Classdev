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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('folder_id');
            $table->unsignedBigInteger('creator_id');
            $table->integer('start');
            $table->integer('end');
            $table->string('color');
            $table->string('title');
            $table->string('description')->nullable();
            $table->timestamps();

            // Relationships

            $table->index('file_id', 'review_file_idx');
            $table->index('folder_id', 'review_folder_idx');
            $table->index('creator_id', 'review_creator_idx');

            $table->foreign('file_id', 'review_file_fk')->on('files')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('folder_id', 'review_folder_fk')->on('folders')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('creator_id', 'review_creator_fk')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
