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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('messenger_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');
            $table->text('content');
            $table->timestamps();

            // Relationships

            $table->index('messenger_id', 'message_messenger_idx');
            $table->index('sender_id', 'message_sender_idx');
            $table->index('recipient_id', 'message_recipient_idx');

            $table->foreign('messenger_id', 'message_messenger_fk')->on('messengers')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sender_id', 'message_sender_fk')->on('users')->references('id');
            $table->foreign('recipient_id', 'message_recipient_fk')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
