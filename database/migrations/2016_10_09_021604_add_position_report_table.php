<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPositionReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acarsdata', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('bid_id')->unsigned();
            $table->foreign('bid_id')->references('id')->on('schedule_complete')->onDelete('cascade');
            $table->double('lat');
            $table->double('lon');
            $table->smallInteger('heading');
            $table->string('altitude');
            $table->integer('groundspeed');
            $table->text('phase');
            $table->text('client');
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
        Schema::drop('acarsdata');
    }
}
