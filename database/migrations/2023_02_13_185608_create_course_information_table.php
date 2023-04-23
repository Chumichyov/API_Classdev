<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

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
            $table->text('image_path')->nullable();
            $table->text('image_name')->nullable();
            $table->text('image_extension')->nullable();
            $table->text('custom_image')->default(0);
            $table->string('code', 20)->unique();
            $table->string('link', 40)->unique();
            $table->timestamps();

            //Relationships
            $table->index('course_id', 'course_information_course_idx');
            $table->foreign('course_id', 'course_information_course_fk')->on('courses')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });

        $faker = Faker::create();

        DB::table('course_information')->insert([
            [
                'course_id' => 1,
                'image_path' => '/storage/backgrounds/1.png',
                'image_name' => '1.png',
                'image_extension' => 'png',
                'code' => strtoupper($faker->bothify('??#?#?')),
                'link' => $faker->bothify('?????##???###?????##'),
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'course_id' => 2,
                'image_path' => '/storage/backgrounds/1.png',
                'image_name' => '1.png',
                'image_extension' => 'png',
                'code' => strtoupper($faker->bothify('??#?#?')),
                'link' => $faker->bothify('?????##???###?????##'),
                'created_at' => Carbon::now()->toDateTimeString()
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
        Schema::dropIfExists('course_information');
    }
};
