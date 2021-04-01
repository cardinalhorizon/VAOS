<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First check if we're doing an existing install.
        Schema::create('airports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('city');
            $table->string('country');
            $table->string('iata')->nullable();
            $table->string('icao');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('image_url');
            $table->longText('data')->nullable(); //JSON Data for All gate information for the system.
            $table->softDeletes();
        });
        Schema::create('aviation_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->default(0);
            $table->string('icao')->nullable();
            $table->string('iata')->nullable();
            $table->string('name');
            $table->json('settings')->nullable();
            $table->boolean('accepting')->default(true);
            $table->boolean('auto_add_new_users')->default(true);
            $table->boolean('visible')->default(true);
            $table->boolean('status')->default(1);
            $table->string('logo_url')->nullable(); // References Storage
            $table->string('widget_url')->nullable(); // References Storage
            $table->string('callsign');
            $table->softDeletes();
        });
        Schema::create('bases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('airport_id')->unsigned();
            $table->foreign('airport_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('aviation_group_id')->unsigned();
            $table->foreign('aviation_group_id')->references('id')->on('aviation_groups')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('aircraft_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('aviation_group_id')->nullable();
            $table->foreign('aviation_group_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->string('name');
            $table->string('icao')->nullable();
            $table->boolean('userdefined');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('aircraft', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('icao');
            $table->string('name');
            $table->string('manufacturer');
            $table->string('registration');
            $table->integer('status');
            $table->integer('base_id')->unsigned()->nullable();
            $table->foreign('base_id')->references('id')->on('airports')->onDelete('set null');
            $table->integer('location_id')->unsigned()->nullable();
            $table->foreign('location_id')->references('id')->on('airports')->onDelete('set null');
            $table->integer('aviation_group_id')->unsigned()->nullable();
            $table->foreign('aviation_group_id')->references('id')->on('aviation_groups')->onDelete('set null');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('aircraft_group_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('aircraft_id')->unsigned();
            $table->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');
            $table->integer('aircraft_group_id')->unsigned();
            $table->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('aviation_group_id')->unsigned();
            $table->foreign('aviation_group_id')->references('id')->on('aviation_groups')->onDelete('cascade');
            $table->string('callsign')->nullable();
            $table->integer('depapt_id')->unsigned();
            $table->foreign('depapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('arrapt_id')->unsigned();
            $table->foreign('arrapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('altapt_id')->unsigned()->nullable();
            $table->foreign('altapt_id')->references('id')->on('airports')->onDelete('cascade');
            $table->integer('aircraft_group_id')->nullable()->unsigned();
            $table->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('set null');
            $table->time('deptime')->nullable();
            $table->time('arrtime')->nullable();
            $table->integer('type');
            $table->boolean('enabled');
            $table->text('defaults')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('fo_id')->unsigned()->nullable();
            $table->foreign('fo_id')->references('id')->on('users')->onDelete('set null');
            $table->string('flight_rules');
            $table->integer('aviation_group_id')->unsigned();
            $table->foreign('aviation_group_id')->references('id')->on('aviation_groups')->onDelete('set null');
            $table->integer('state');
            $table->string('callsign');
            $table->integer('depapt_id')->unsigned();
            $table->foreign('depapt_id')->references('id')->on('airports')->onDelete('set null');
            $table->integer('arrapt_id')->unsigned();
            $table->foreign('arrapt_id')->references('id')->on('airports')->onDelete('set null');
            $table->integer('altapt_id')->unsigned()->nullable();
            $table->foreign('altapt_id')->references('id')->on('airports')->onDelete('set null');
            $table->integer('aircraft_id')->unsigned();
            $table->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('set null');
            $table->json('route_data')->nullable();
            $table->time('deptime')->nullable();
            $table->time('arrtime')->nullable();
            $table->integer('status')->nullable();
            $table->string('network')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->integer('groundspeed')->nullable();
            $table->integer('altitude')->nullable();
            $table->smallInteger('distance')->nullable();
            $table->integer('type')->nullable();
            $table->timestamps();
        });
        Schema::create('flight_telemetry', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('flight_id');
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
            $table->double('latitude');
            $table->double('longitude');
            $table->smallInteger('heading');
            $table->integer('altitude');
            $table->integer('groundspeed');
            $table->string('client');
            $table->json('client_specific_data')->nullable();
            $table->timestamps();
        });
        Schema::create('flight_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('flight_id');
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
            $table->string('type');
            $table->json('data');
            $table->timestamps();
        });
        Schema::create('flight_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('flight_id');
            $table->string('type');
            $table->json('data');
            $table->timestamps();
        });
        Schema::create('flight_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('flight_id')->unsigned();
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('comment');
            $table->integer('type');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('aircraft_schedule', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('aircraft_id');
            $t->unsignedInteger('schedule_id');
            $t->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');
            $t->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
        });
        Schema::create('aircraft_group_schedule', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('aircraft_group_id');
            $t->unsignedInteger('schedule_id');
            $t->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('cascade');
            $t->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $t->boolean('primary');
        });
        Schema::create('type_ratings', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('aviation_group_id')->nullable();
            $t->foreign('aviation_group_id')->references('id')->on('airlines')->onDelete('set null');
            $t->string('name');
            $t->string('code'); // Abbreviation
            $t->text('description')->nullable();
            $t->string('icon_url')->nullable();
            $t->timestamps();
        });
        Schema::create('type_rating_user', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('user_id');
            $t->unsignedInteger('type_rating_id');
            $t->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $t->foreign('type_rating_id')->references('id')->on('type_ratings')->onDelete('cascade');
            $t->timestamps();
        });
        Schema::create('aircraft_group_type_rating', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('aircraft_group_id');
            $t->unsignedInteger('type_rating_id');
            $t->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('cascade');
            $t->foreign('type_rating_id')->references('id')->on('type_ratings')->onDelete('cascade');
        });
        Schema::create('aircraft_type_rating', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('aircraft_id');
            $t->unsignedInteger('type_rating_id');
            $t->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');
            $t->foreign('type_rating_id')->references('id')->on('type_ratings')->onDelete('cascade');
        });
        Schema::create('user_external_hours', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('user_id');
            $t->integer('type')->default(0);
            $t->string('name');
            $t->decimal('total');
            $t->string('source_url')->nullable();
            $t->decimal('ptt')->nullable();
            $t->boolean('dynamic_pull')->nullable();
            $t->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $t->boolean('approved')->nullable();
        });
        Schema::create('aviation_group_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('aviation_group_id');
            $table->unsignedInteger('base_id')->nullable();
            $table->string('pilot_id')->nullable();
            $table->foreign('aviation_group_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('status');
            $table->boolean('primary');
            $table->boolean('admin');
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
