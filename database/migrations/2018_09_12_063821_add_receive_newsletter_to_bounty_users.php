<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiveNewsletterToBountyUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('bounty_users', function($table) {
            $table->integer('receive_newsletter')->default(1)->after('card_interest');
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
        Schema::table('bounty_users', function($table) {
            $table->dropColumn('receive_newsletter');
        });
    }
}
