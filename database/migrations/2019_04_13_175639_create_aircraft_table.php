<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAircraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aircraft', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('aviation_group_id')->nullable();
            $table->string('registration');
            $table->string('icao');
            $table->string('variant')->nullable();
            $table->unsignedBigInteger('icao_variant_id')->nullable();
            $table->json('config')->nullable();
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
        Schema::dropIfExists('aircraft');
    }
}
