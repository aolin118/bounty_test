<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBountyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bounty_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->integer('referrer')->nullable();
            $table->string('unique_link')->unique();
            $table->integer('telegram_completed')->default(0);
            $table->integer('twitter_completed')->default(0);
            $table->integer('youtube_completed')->default(0);
            $table->integer('reddit_completed')->default(0);
            $table->integer('medium_completed')->default(0);
            $table->integer('card_interest')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bounty_users');
    }
}
