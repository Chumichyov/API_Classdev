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
            $table->string('mode');
            $table->timestamps();
        });

        DB::table('modes')->insert([
            ['mode' => 'csharp'],
            ['mode' => 'javascript'],
            ['mode' => 'php'],
            ['mode' => 'html'],
            ['mode' => 'css'],
            ['mode' => 'text'],
            ['mode' => 'sql'],
            ['mode' => 'c_cpp'],
            ['mode' => 'python'],
            ['mode' => 'golang'],
            ['mode' => 'json'],
            ['mode' => 'java'],
            ['mode' => 'kotlin'],
            ['mode' => 'perl'],
            ['mode' => 'rust'],
            ['mode' => 'ruby'],
            ['mode' => 'swift'],
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
