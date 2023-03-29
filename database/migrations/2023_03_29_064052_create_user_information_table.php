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
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('bio')->nullable();
            $table->string('photo_path');
            $table->timestamps();

            //Relationships
            $table->index('user_id', 'user_information_user_idx');
            $table->foreign('user_id', 'user_information_user_fk')->on('users')->references('id');
        });

        DB::table('user_information')->insert([
            [
                'user_id' => 1,
                'photo_path' => '/storage/photo/1.jpg'
            ],
            [
                'user_id' => 2,
                'photo_path' => '/storage/photo/1.jpg'
            ],
            [
                'user_id' => 3,
                'photo_path' => '/storage/photo/1.jpg'
            ],
            [
                'user_id' => 4,
                'photo_path' => '/storage/photo/1.jpg'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_information');
    }
};
