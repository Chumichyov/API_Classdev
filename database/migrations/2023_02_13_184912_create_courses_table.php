<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('group');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('leader_id');
            $table->timestamps();

            //Relationships
            $table->index('leader_id', 'course_leader_idx');
            $table->foreign('leader_id', 'course_leader_fk')->on('users')->references('id');
        });

        DB::table('courses')->insert([
            [
                'title' => 'Создание веб-приложений',
                'group' => '49ИС1-2Д',
                'description' => 'Создание веб-приложений на основе MVC системы Laravel и фремфорка для создания пользовательский веб-приложений Vue. В данном курсе вы научитесь создавать API систему и научитесь подключаться к ней используя Vue.',
                'leader_id' => 2,
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'title' => 'Оптимизация веб-приложений',
                'group' => '49ИС1-2Д',
                'description' => 'Создание веб-приложений на основе MVC системы Laravel и фремфорка для создания пользовательский веб-приложений Vue. В данном курсе вы научитесь создавать API систему и научитесь подключаться к ней используя Vue.',
                'leader_id' => 2,
                'created_at' => Carbon::now()->toDateTimeString()
            ],
        ]);

        $path = '/public/courses/course_1';

        //Main course folder
        Storage::makeDirectory($path);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
