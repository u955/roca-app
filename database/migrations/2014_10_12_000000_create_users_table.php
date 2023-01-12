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

            $table->string('roca-id');
            $table->dateTime('updated-at');

            $table->string('youtube-id');
            $table->string('youtube-nickname');
            $table->string('youtube-name')->nullable();
            $table->string('youtube-email')->nullable();
            $table->string('youtube-avatar');

            $table->string('youtube-refresh-token');
            $table->string('youtube-access-token');
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
