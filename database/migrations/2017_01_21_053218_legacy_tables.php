<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LegacyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legacy_bids', function (Blueprint $table) {
            $table->increments('bidid');
            $table->integer('pilotid');
            $table->integer('routeid');
            $table->date('dateadded');
        });
        Schema::create('legacy_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code');
            $table->string('flightnum');
            $table->string('depicao', 4);
            $table->string('arricao', 4);
            $table->text('route');
            $table->text('route_details');
            $table->text('aircraft');
            $table->string('flightlevel')->nullable();
            $table->float('distance')->nullable();
            $table->string('deptime')->nullable();
            $table->string('arrtime')->nullable();
            $table->float('flighttime')->default('0');
            $table->string('daysofweek')->default('0123456');
            $table->float('price')->default('0');
            $table->float('payforflight')->default('0');
            $table->string('flighttype')->default('P');
            $table->integer('timesflown')->default('0');
            $table->text('notes')->nullable();
            $table->integer('enabled')->default('1');
            $table->integer('bidid')->default('0');
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
