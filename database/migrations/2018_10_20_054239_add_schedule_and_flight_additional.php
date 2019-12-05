<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleAndFlightAdditional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flights', function (Blueprint $t) {
            $t->string('rules')->nullable();
            $t->string('callsign')->nullable();
        });
        Schema::table('schedules', function (Blueprint $t) {
            $t->string('callsign')->nullable();
        });
        Schema::table('airlines', function (Blueprint $t) {
            $t->integer('type')->nullable()->default(0);
        });
        Schema::table('airports', function (Blueprint $t) {
            $t->string('image_url')->nullable();
        });
        Schema::create('ext_hours', function (Blueprint $t) {
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
