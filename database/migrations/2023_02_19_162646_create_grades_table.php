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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('symbol');
            $table->timestamps();
        });

        DB::table('grades')->insert([
            ['title' => 'Единица', 'symbol' => '1'],
            ['title' => 'Двойка', 'symbol' => '2'],
            ['title' => 'Тройка', 'symbol' => '3'],
            ['title' => 'Четверка', 'symbol' => '4'],
            ['title' => 'Пятерка', 'symbol' => '5'],
            ['title' => 'Без оценки', 'symbol' => ''],
            ['title' => 'Изменить', 'symbol' => ''],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grades');
    }
};
