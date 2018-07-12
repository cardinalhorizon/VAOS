<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MajorUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('airlines', function (Blueprint $table) {
            $table->boolean('autoAccept')->nullable();
            $table->boolean('isAccepting')->nullable();
            $table->boolean('autoAdd')->nullable();
            // Settings
            $table->boolean('aaEnabled')->nullable();
            $table->integer('aaLandingRate')->nullable();
            $table->string('banner')->nullable();
            $table->integer('status')->nullable();
        });
        Schema::table('aircraft', function (Blueprint $table) {
            $table->integer('aaLandingRate')->nullable();
            $table->unsignedInteger('user_id')->nullable();
        });

        Schema::create('airline_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('airline_id');
            $table->unsignedInteger('hub_id');
            $table->integer('pilot_id')->nullable();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('status');
            $table->boolean('primary');
            $table->boolean('admin');
        });

        Schema::table('aircraft_groups', function (Blueprint $table) {
            $table->unsignedInteger('airline_id')->nullable();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
        });
        Schema::table('flights', function (Blueprint $table) {
            $table->string('wcBidID')->nullable();
            $table->integer('status')->nullable();
            $table->string('network')->nullable();
            $table->double('lat')->nullable();
            $table->double('lon')->nullable();
            $table->integer('gs')->nullable();
            $table->integer('altitude')->nullable();
            $table->smallInteger('distance')->nullable();
            $table->integer('airline_id')->unsigned()->nullable()->change();
            $table->integer('aircraft_id')->unsigned()->nullable()->change();
            $table->integer('type')->nullable();
        });
        Schema::create('system_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('value');
            $table->string('fancy_name');
            $table->string('type');
            $table->string('page');
            $table->text('description')->nullable();
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
