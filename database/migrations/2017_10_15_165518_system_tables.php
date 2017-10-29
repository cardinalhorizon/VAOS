<?php
/**
 * VAOS 1.1 Updater and Installation Migration
 *
 * This file contains both an update schema for the existing database or the new schema which will be used in the 1.1
 * product line. This update contains new things, including FS Hub integration which will be coming in 1.0.1.
 *
 * For More Information, visit http://fsvaos.net/docs
 */

use Illuminate\Support\Facades\Schema;
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
        if (!Schema::hasTable('airports')) {

            /*
             * Virtual Airline Operations System 1.1 Fresh Install Schema
             */
            Schema::create('airports', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('city');
                $table->string('country');
                $table->string('iata');
                $table->string('icao');
                $table->double('lat');
                $table->double('lon');
                $table->softDeletes();
            });

            Schema::create('airlines', function (Blueprint $table) {
                $table->increments('id');
                $table->string('icao');
                $table->string('fshub_id');
                $table->string('iata')->nullable();
                $table->string('name');
                $table->string('logo')->nullable(); // References Storage
                $table->string('widget')->nullable(); // References Storage
                $table->string('callsign');
                $table->softDeletes();
            });

            Schema::create('hubs', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('airport_id')->unsigned();
                $table->foreign('airport_id')->references('id')->on('airports')->onDelete('cascade');
                $table->integer('airline_id')->unsigned();
                $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
                $table->timestamps();
                $table->softDeletes();
            });

            Schema::create('aircraft', function (Blueprint $table) {
                $table->increments('id');
                $table->string('icao');
                $table->integer('type');
                $table->string('name');
                $table->string('manufacturer');
                $table->string('registration');
                $table->integer('status');
                $table->integer('hub_id')->unsigned()->nullable();
                $table->foreign('hub_id')->references('id')->on('hubs')->onDelete('set null');
                $table->integer('location_id')->unsigned()->nullable();
                $table->foreign('location_id')->references('id')->on('airports')->onDelete('set null');
                $table->integer('airline_id')->unsigned()->nullable();
                $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('set null');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            Schema::create('aircraft_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('airline_id');
                $table->string('name');
                $table->string('icao')->nullable();
                $table->boolean('userdefined');
                $table->text('description');
                $table->timestamps();
                $table->softDeletes();
            });

            Schema::create('aircraft_group_pivot', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('aircraft_id')->unsigned();
                $table->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');
                $table->integer('aircraft_group_id')->unsigned();
                $table->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('cascade');
                $table->timestamps();
                $table->softDeletes();
            });
            /*
             * New to 1.1, Type ratings are replacing the ranking system commonly found in phpVMS installations.
             * Type ratings can do the following:
             *
             * - Put Restrictions on aircraft based on Aircraft ICAO
             * - Restrict aircraft based on aircraft groups
             */
            Schema::create('type_ratings', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('airline_id');
                $table->string('name');
                $table->string('identifier');
                $table->text('description');
                $table->timestamps();
                $table->softDeletes();
            });

            /*
             * Schedule System
             *
             * A major change in the scheduling system is how routes are implemented. Columns have been removed in favor
             * of entire pivot tables to provide a deeper functionality. Most notable is that you can now assign
             * multiple aircraft groups and individual aircraft to a route.
             */
            Schema::create('scheduled_flights', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('airline_id')->unsigned();
                $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
                $table->string('flightnum');
                $table->string('sub')->nullable();
                $table->integer('depapt_id')->unsigned();
                $table->foreign('depapt_id')->references('id')->on('airports')->onDelete('cascade');
                $table->integer('arrapt_id')->unsigned();
                $table->foreign('arrapt_id')->references('id')->on('airports')->onDelete('cascade');
                $table->boolean('seasonal');
                $table->date('startdate')->nullable(); // For seasonal route system
                $table->date('enddate')->nullable(); // For seasonal route system
                $table->time('deptime')->nullable(); // Store in UTC
                $table->time('arrtime')->nullable(); // Store in UTC
                $table->integer('type');
                $table->boolean('enabled');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            // New in the 1.1 update, You can now assign multiple aircraft groups and aircraft to a single route.
            Schema::create('aircraft_group_scheduled_flight', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('aircraft_group_id')->unsigned();
                $table->foreign('aircraft_group_id')->references('id')->on('aircraft_group')->onDelete('set null');
                $table->integer('scheduled_flight_id')->unsigned();
                $table->foreign('scheduled_flight_id')->references('id')->on('scheduled_flights')->onDelete('cascade');
                $table->boolean('primary');
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create('aircraft_scheduled_flight', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('aircraft_id')->unsigned();
                $table->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');
                $table->integer('scheduled_flight_id')->unsigned();
                $table->foreign('scheduled_flight_id')->references('id')->on('scheduled_flights')->onDelete('cascade');
                $table->boolean('primary');
                $table->timestamps();
                $table->softDeletes();
            });
            /*
             * Bids Section. Major overhaul in the 1.1 update for data management. The following features are now added.
             *
             * - Multi-crew Bidding
             * - Dispatcher Assignments
             * - Schedule Block Times
             * - ETOPS Flag with ETOPS Alternate Airports Support
             * - Multiple Alternate Airports with a priority list
             * - Aircraft pax, center of gravity and fuel load
             * - Attach paperwork (documents, PDFs, images, etc) to the flight
             * - Comments section for questions between dispatchers and pilots.
             */
            Schema::create('bids', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('status');
                $table->integer('airline_id')->unsigned();
                $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
                $table->string('flightnum');
                $table->string('sub')->nullable();
                $table->integer('captain_id')->unsigned();
                $table->foreign('captain_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('fo_id')->unsigned()->nullable();
                $table->foreign('fo_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('dispatcher_id')->unsigned()->nullable();
                $table->foreign('dispatcher_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('depapt_id')->unsigned();
                $table->foreign('depapt_id')->references('id')->on('airports')->onDelete('cascade');
                $table->integer('arrapt_id')->unsigned();
                $table->foreign('arrapt_id')->references('id')->on('airports')->onDelete('cascade');
                $table->integer('aircraft_id')->unsigned();
                $table->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');

                $table->integer('bid_type');

                // Insert Alternate Airport List table
                $table->time('scheduled_out')->nullable();
                $table->time('scheduled_off')->nullable();
                $table->time('scheduled_on')->nullable();
                $table->time('scheduled_in')->nullable();

                // Route Information

                $table->string('route');
                $table->integer('cruise_alt')->nullable();
                $table->boolean('etopsRequired');
                // Aircraft Settings
                $table->integer('pax');
                $table->integer('cg');
                $table->double('cg_percent');
                $table->integer('fuel_block');
                $table->integer('fuel_reserve');
                $table->integer('gross_weight');

                // FS Hub & WebCARS Information
                $table->string('globalID');
            });
            Schema::create('bid_alternates', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('bid_id');
                $table->unsignedInteger('airport_id');
            });
            Schema::create('bid_paperworks', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('bid_id');
                $table->boolean('isLocal');
                $table->string('file_url');
                $table->string('name');
                $table->string('type');
            });
            Schema::create('bid_comments', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->text('message');
                $table->timestamps();
            });

            /*
             * Formerly the PIREPs table, the Logbook has been redesigned entirely to expand to ACARS client specific
             * features.
             *
             * As an optional checkbox in the user interface, JSON columns will be added if MySQL 5.7 is on the hosted
             * server. The purpose of these columns is to freeze all the foreign key information in time.
             */
            Schema::create('logbook_entries', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('status');
                $table->string('client');
                $table->unsignedInteger('airline_id');
                $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('set null');
                $table->string('flightnum');
                $table->string('sub')->nullable();
                $table->unsignedInteger('captain_id');
                $table->foreign('captain_id')->references('id')->on('users')->onDelete('set null');
                $table->unsignedInteger('fo_id')->nullable();
                $table->foreign('fo_id')->references('id')->on('users')->onDelete('set null');
                $table->unsignedInteger('dispatcher_id')->nullable();
                $table->foreign('dispatcher_id')->references('id')->on('users')->onDelete('set null');
                $table->unsignedInteger('aircraft_id')->nullable();
                $table->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('set null');

                $table->unsignedInteger('depapt_id');
                $table->foreign('depapt_id')->references('id')->on('airports')->onDelete('set null');
                $table->unsignedInteger('arrapt_id');
                $table->foreign('arrapt_id')->references('id')->on('airports')->onDelete('set null');

                $table->boolean('landedAtAlt');

                // Insert Alternate Airport List table

                $table->time('scheduled_out')->nullable();
                $table->time('scheduled_off')->nullable();
                $table->time('scheduled_on')->nullable();
                $table->time('scheduled_in')->nullable();


                $table->time('actual_out')->nullable();
                $table->time('actual_off')->nullable();
                $table->time('actual_on')->nullable();
                $table->time('actual_in')->nullable();

                $table->string('route');
                $table->integer('cruise_alt')->nullable();
                // Aircraft specific details
                $table->integer('landingrate')->nullable();
                $table->integer('fuelused')->nullable();
                $table->text('clientLogRaw')->nullable(); // for smartCARS
            });
            Schema::create('logbook_alternates', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('logbook_entry_id');
                $table->unsignedInteger('airport_id');
            });
            Schema::create('logbook_comments', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->text('message');
                $table->timestamps();
            });
            /*
             * Flight Telemetry is now stored in it's own dedicated table which is shared between the bid and logbook.
             *
             * While the flight is in progress, the data entry will rely on the bid. Upon filing the logbook entry,
             * the bid will then be removed, being replaced by the logbook entry id.
             *
             * Active moving maps therefore can use the last data point as the approximate current position of the
             * aircraft in flight, in addition to plotting the actual flight flown in progress along with the planned
             * route.
             */

            Schema::create('telemetry_points', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('bid_id')->nullable();
                $table->unsignedInteger('logbook_entry_id')->nullable();
                $table->integer('alt');
                $table->integer('speed');
                $table->decimal('lat');
                $table->decimal('lon');
                $table->integer('heading');
                $table->integer('vs');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airports');
        Schema::dropIfExists('airlines');
        Schema::dropIfExists('hubs');
        Schema::dropIfExists('aircraft');
        Schema::dropIfExists('aircraft_groups');
        Schema::dropIfExists('aircraft_group_pivot');
        Schema::dropIfExists('type_ratings');
        Schema::dropIfExists('scheduled_flights');
        Schema::dropIfExists('aircraft_group_scheduled_flight');
        Schema::dropIfExists('aircraft_scheduled_flight');
        Schema::dropIfExists('bids');
        Schema::dropIfExists('bid_alternates');
        Schema::dropIfExists('bid_paperworks');
        Schema::dropIfExists('bid_comments');
        Schema::dropIfExists('logbook_entries');
        Schema::dropIfExists('logbook_alternates');
        Schema::dropIfExists('logbook_comments');
        Schema::dropIfExists('telemetry_points');
    }
}
