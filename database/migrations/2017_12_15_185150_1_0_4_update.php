<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update104 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('airlines', function (Blueprint $table) {
            $table->boolean('autoAccept');
            $table->boolean('isAccepting');
            $table->boolean('autoAdd');
        });
        Schema::table('airline_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('airline_id');
            $table->integer('pilotid')->nullable();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('status');
            $table->boolean('primary');
            $table->boolean('staff');
        });
        Schema::table('aircraft_groups', function (Blueprint $table) {
            $table->unsignedInteger('airline_id');
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
        });
        Schema::table('schedule_complete', function (Blueprint $table) {
            $table->string('wcBidID');
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
