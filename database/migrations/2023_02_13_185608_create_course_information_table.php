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
        Schema::create('course_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->text('photo_path')->default('http://dummyimage.com/500x237');
            $table->text('photo_name')->nullable();
            $table->string('code', 20);
            $table->string('link', 40);
            $table->timestamps();

            //Relationships
            $table->index('course_id', 'course_information_course_idx');
            $table->foreign('course_id', 'course_information_course_fk')->on('courses')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_information');
    }
};
