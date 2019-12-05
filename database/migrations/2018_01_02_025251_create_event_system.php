<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airline_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('airline_id')->nullable();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('set null');
            $table->string('name');
            $table->string('url_slug');
            $table->text('description');
            $table->unsignedInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->string('banner_url')->nullable();
            $table->integer('max_users')->nullable();
            $table->dateTimeTz('start');
            $table->dateTimeTz('end');
            $table->integer('access'); // 0 = Private, 1 = Unlisted, 2 = Public
            $table->integer('scope'); // 0 = Single Airline, 1 = System Wide, 2 = Global (via FS Hub)
            $table->integer('status'); // 0 = Hidden, 1 = Published, 2 = Complete
            $table->boolean('ignoreTypeRatings');
            $table->boolean('publishToNetwork'); // Publishes to VATSIM/IVAO Event Networks.
        });
        Schema::create('airline_event_flights', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('airline_event_id');
            $table->boolean('isGroupFlight'); // This flag means multiple people can bid on this flight.
            $table->unsignedInteger('airline_id')->nullable();
            $table->unsignedInteger('captain_id')->nullable();
            $table->unsignedInteger('fo_id')->nullable();
            $table->string('flightnum')->nullable();
            $table->unsignedInteger('depapt_id');
            $table->unsignedInteger('arrapt_id');
            $table->string('route')->nullable();
            $table->integer('max_users')->nullable();

            $table->foreign('airline_event_id')->references('id')->on('airline_events')->onDelete('cascade');
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('set null');
            $table->foreign('captain_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('fo_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('depapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->foreign('arrapt_id')->references('id')->on('airports')->onDelete('cascade');
        });
        /*
         * Pivot Tables Below
         */
        Schema::create('airline_event_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('airline_event_id');
            $table->integer('status'); // 0 = Interested, 1 = Confirmed

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('airline_event_id')->references('id')->on('airline_events')->onDelete('cascade');
        });
        Schema::create('aircraft_event_flight', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('airline_event_flight_id');
            $table->unsignedInteger('aircraft_id');

            $table->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');
            $table->foreign('airline_event_flight_id')->references('id')->on('airline_event_flights')->onDelete('cascade');
        });
        Schema::create('aircraft_group_event_flight', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('airline_event_flight_id');
            $table->unsignedInteger('aircraft_group_id');

            $table->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('cascade');
            $table->foreign('airline_event_flight_id', 'event_flight_foreign')->references('id')->on('airline_event_flights')->onDelete('cascade');
        });
        Schema::create('airline_event_flight_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('airline_event_flight_id');
            $table->unsignedInteger('user_id');
            $table->integer('status'); // 0 = Interested, 1 = Confirmed

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('airline_event_flight_id', 'event_flight_foreign2')->references('id')->on('airline_event_flights')->onDelete('cascade');
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
