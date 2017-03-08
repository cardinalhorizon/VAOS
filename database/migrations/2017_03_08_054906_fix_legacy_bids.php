<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixLegacyBids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('legacy_bids');
        Schema::create('legacy_create', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parentid');
            $table->integer('pilotid');
            $table->integer('routeid');
            $table->date('dateadded');
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
    }
}
