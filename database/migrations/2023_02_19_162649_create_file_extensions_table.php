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
        Schema::create('file_extensions', function (Blueprint $table) {
            $table->id();
            $table->string('extension');
            $table->unsignedBigInteger('mode_id');
            $table->timestamps();

            // Relationships

            $table->index('mode_id', 'file_extension_mode_idx');
            $table->foreign('mode_id', 'file_extension_mode_fk')->on('modes')->references('id');
        });

        DB::table('file_extensions')->insert([
            ['extension' => 'cs', 'mode_id' => '1'],
            ['extension' => 'js', 'mode_id' => '2'],
            ['extension' => 'php', 'mode_id' => '3'],
            ['extension' => 'html', 'mode_id' => '4'],
            ['extension' => 'css', 'mode_id' => '5'],
            ['extension' => 'txt', 'mode_id' => '6'],
            ['extension' => 'sql', 'mode_id' => '7'],
            ['extension' => 'cpp', 'mode_id' => '8'],
            ['extension' => 'c', 'mode_id' => '8'],
            ['extension' => 'py', 'mode_id' => '9'],
            ['extension' => 'go', 'mode_id' => '10'],
            ['extension' => 'json', 'mode_id' => '11'],
            ['extension' => 'java', 'mode_id' => '12'],
            ['extension' => 'kt', 'mode_id' => '13'],
            ['extension' => 'kts', 'mode_id' => '13'],
            ['extension' => 'ktm', 'mode_id' => '13'],
            ['extension' => 'pl', 'mode_id' => '14'],
            ['extension' => 'rs', 'mode_id' => '15'],
            ['extension' => 'rb', 'mode_id' => '16'],
            ['extension' => 'swift', 'mode_id' => '17'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_extensions');
    }
};
