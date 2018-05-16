<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferrerToTwitterBountyUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('twitter_bounty_users', function($table) {
            $table->string('referrer')->nullable()->after('twitter_followers_count');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('twitter_bounty_users', function($table) {
            $table->dropColumn('referrer');
        });
    }
}
