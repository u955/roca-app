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
        Schema::create('live_chat_message_log', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->timestamp('published_at');

            $table->string('message');
            $table->string('judgement');

            $table->decimal('neutral');
            $table->decimal('slander');
            $table->decimal('sarcasm');
            $table->decimal('sexual');
            $table->decimal('spam');
            $table->decimal('divulgation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_chat_message_log');
    }
};
