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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->rememberToken();
            $table->timestamps();

            $table->string('user_key');

            $table->string('youtube_id');
            $table->string('youtube_nickname');
            $table->string('youtube_name')->nullable();
            $table->string('youtube_email')->nullable();
            $table->string('youtube_avatar');

            $table->string('youtube_refresh_token');
            $table->string('youtube_access_token');
        });
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
