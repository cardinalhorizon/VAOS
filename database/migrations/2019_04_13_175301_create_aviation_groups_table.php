<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAviationGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aviation_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type'); // 0 = 121, 1 = 135
            $table->string('name');
            $table->string('settings_file')->nullable();
            $table->string('icao')->nullable();
            $table->string('iata')->nullable();
            $table->boolean('joinable');
            $table->boolean('auto_join');
            $table->boolean('active');
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
        Schema::dropIfExists('aviation_groups');
    }
}
