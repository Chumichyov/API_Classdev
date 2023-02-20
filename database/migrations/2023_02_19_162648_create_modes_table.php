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
        Schema::create('modes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        DB::table('modes')->insert([
            ['title' => 'csharp'],
            ['title' => 'javascript'],
            ['title' => 'php'],
            ['title' => 'html'],
            ['title' => 'css'],
            ['title' => 'text'],
            ['title' => 'sql'],
            ['title' => 'c_cpp'],
            ['title' => 'python'],
            ['title' => 'golang'],
            ['title' => 'json'],
            ['title' => 'java'],
            ['title' => 'kotlin'],
            ['title' => 'perl'],
            ['title' => 'rust'],
            ['title' => 'ruby'],
            ['title' => 'swift'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modes');
    }
};
