<?php

use Carbon\Carbon;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('title', 64);
            $table->text('description');
            $table->unsignedBigInteger('type_id')->default(1);
            $table->timestamps();

            //Relationships
            $table->index('course_id', 'task_course_idx');
            $table->index('type_id', 'task_type_idx');

            $table->foreign('course_id', 'task_course_fk')->on('courses')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id', 'task_type_fk')->on('task_types')->references('id');
        });

        DB::table('tasks')->insert([
            [
                'course_id' => '1',
                'title' => 'Создание CRUD-приложения Todo. Часть 1.',
                'description' => 'Laravel - это MVC фреймворк для быстрого, удобного и, главное, правильного написания сайтов на языке PHP. Фреймворк обладает большим набором функций, плагинов и шаблонов, которые позволяют воплощать даже самые амбициозные проекты в жизнь. Laravel позволяет выполнить такие действия, как: Unit тестирование, отслеживание URL адресов, установка безопасности, работа с сессиями и создание системы авторизации, легкая работа с базой данных, работа с почтой, отслеживание ошибок и еще множество других вещей. Все это возможно реализовать и без Laravel, но используя его вы будете использовать уже готовые решения, а также ваш код получится намного проще и меньше, нежели писать все самостоятельно.',
                'type_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'course_id' => '1',
                'title' => 'Создание CRUD-приложения Todo. Часть 2.',
                'description' => 'Laravel имеет дополнительный инструментарий для подключения различных препроцессоров для разработки фронтенда приложения и некоторые шаблоны. Laravel позволяет подключить Bootstrap, React и/или Vue для разработки. Для этого используется менеджер пакетов npm, поскольку данные инструменты используют язык программирования JavaScript (или его разновидность TypeScript), который работает на платформе `Node.js`',
                'type_id' => 2,
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
        Schema::dropIfExists('tasks');
    }
};
