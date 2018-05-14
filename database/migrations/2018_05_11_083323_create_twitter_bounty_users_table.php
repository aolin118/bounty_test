<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwitterBountyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_bounty_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('twitter_username');
            $table->string('twitter_id')->unique();
            $table->integer('twitter_followers_count')->default(0);
            $table->string('eth_address')->unique();
            $table->integer('is_following')->default(0);
            $table->integer('has_retweeted')->default(0);
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
        Schema::dropIfExists('twitter_bounty_users');
    }
}
