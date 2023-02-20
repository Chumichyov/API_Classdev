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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('role_id')->default(1);
            $table->rememberToken();
            $table->timestamps();

            //Relationships
            $table->index('role_id', 'user_role_idx');
            $table->foreign('role_id', 'user_role_fk')->on('roles')->references('id');
        });

        DB::table('users')->insert([
            [
                'name' => 'Егор',
                'surname' => 'Чумичёв',
                'email' => 'chumichyooov@mail.ru',
                'password' => '$2y$10$1dDU1MJ0h5Bn1Ea3hekXUuOs7JyL1dGj8RO.nk8MUG8fqZWvwEGje',
                'role_id' => 2,
            ],
            [
                'name' => 'Кузьма',
                'surname' => 'Симонов',
                'email' => 'kuza.simonov@mail.ru',
                'password' => '$2y$10$1dDU1MJ0h5Bn1Ea3hekXUuOs7JyL1dGj8RO.nk8MUG8fqZWvwEGje',
                'role_id' => 1,
            ],
            [
                'name' => 'Алиса',
                'surname' => 'Тихонова',
                'email' => 'alisa@mail.ru',
                'password' => '$2y$10$1dDU1MJ0h5Bn1Ea3hekXUuOs7JyL1dGj8RO.nk8MUG8fqZWvwEGje',
                'role_id' => 1,
            ],
            [
                'name' => 'Света',
                'surname' => 'Якунина',
                'email' => 'sveta@mail.ru',
                'password' => '$2y$10$mtlXWMe9gQriTxtP8SyCQOn8yQoFE61/fUrSe8pnUVotU4oQmeGU6',
                'role_id' => 1,
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
        Schema::dropIfExists('users');
    }
};
