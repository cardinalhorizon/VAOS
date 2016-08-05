<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('flightnum');
            $table->string('depicao');
            $table->string('arricao');
            $table->string('alticao')->nullable();
            $table->string('route')->nullable();
            $table->string('aircraft')->nullable();
            $table->string('daysofweek');
            $table->integer('type');
            $table->boolean('enabled');
            $table->timestamps();
        });
        Schema::create('pirep', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pilotid')->unsigned();
            $table->foreign('pilotid')->references('id')->on('users')->onDelete('cascade');
            $table->text('schedule'); //JSON Storage
            $table->text('flightdata'); //JSON Storage
            $table->timestamps();
        });
        Schema::create('airports', function (Blueprint $table) {
        	$table->increments('id');
        	$table->string('name');
        	$table->string('city');
        	$table->string('country');
        	$table->string('iata');
        	$table->string('icao');
        	$table->double('lat');
        	$table->double('lon');
        	$table->longText('data')->nullable(); //JSON Data for All gate information for the system.
        });
        Schema::create('hubs', function (Blueprint $table) {
        	$table->increments('id');
        	$table->integer('airport')->unsigned();
        	$table->foreign('airport')->references('id')->on('airports')->onDelete('cascade');
        });
        Schema::create('aircraft', function (Blueprint $table) {
        	$table->increments('id');
        	$table->string('icao');
        	$table->string('name');
        	$table->string('manufacturer');
        	$table->string('registration');
        	$table->integer('range');
        	$table->integer('maxpax');
        	$table->integer('maxgw');
        	$table->boolean('enabled');
        	$table->integer('hub')->unsigned();
        	$table->integer('location')->unsigned();
        	$table->foreign('location')->references('id')->on('airports')->onDelete('cascade');
        	$table->timestamps();
        });
        Schema::create('settings', function (Blueprint $table) {
        	$table->increments('id');
        	$table->string('friendlyname');
        	$table->string('name');
        	$table->string('value');
        });
        Schema::create('bids', function (Blueprint $table) {
        	$table->increments('id');
        	$table->integer('pilotid')->unsigned();
        	$table->foreign('pilotid')->references('id')->on('users')->onDelete('cascade');
        	$table->integer('scheduleid')->unsigned();
        	$table->foreign('scheduleid')->references('id')->on('schedules')->onDelete('cascade');
        	$table->timestamps();
        });
        Schema::create('api_keys', function (Blueprint $table) {
        	$table->increments('id');
        	$table->string('name');
        	$table->string('key')->unique();
        	$table->timestamps();
        });
        Schema::create('api_routes', function (Blueprint $table) {
        	$table->increments('id');
        	$table->string('verb');
        	$table->string('route');
        	$table->text('description');
        	$table->timestamps();
        });
        Schema::create('api_perms', function (Blueprint $table) {
        	$table->increments('id');
        	$table->integer('apikey')->unsigned();
        	$table->foreign('apikey')->references('id')->on('api_keys')->onDelete('cascade');
        	$table->integer('apiroute')->unsigned();;
        	$table->foreign('apiroute')->references('id')->on('api_routes')->onDelete('cascade');
        	$table->boolean('enabled');
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
        //
        Schema::drop('schedules');
        Schema::drop('pirep');
        Schema::drop('airports');
        Schema::drop('aircraft');
        Schema::drop('settings');
        Schema::drop('bids');
        Schema::drop('api_keys');
        Schema::drop('api_rotues');
        Schema::drop('api_perms');
    }
}
