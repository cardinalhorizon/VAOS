<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('aviation_group_id')->nullable();
            $table->string('callsign');
            $table->string('rules');
            $table->unsignedInteger('depapt_id');
            $table->unsignedInteger('arrapt_id');
            $table->unsignedInteger('altapt_id')->nullable();
            $table->unsignedInteger('aircraft_id');
            $table->json('route_data')->nullable();
            $table->time('deptime')->nullable();
            $table->time('arrtime')->nullable();
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
        Schema::dropIfExists('flights');
    }
}
