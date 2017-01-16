<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->string('pid')->nullable();
            $table->integer('hub_id')->nullable()->unsigned();
            $table->foreign('hub_id')->references('id')->on('hubs')->onDelete('set null');
            $table->integer('location_id')->nullable()->unsigned();
            $table->foreign('location_id')->references('id')->on('airports')->onDelete('set null');
            $table->double('totalhours')->nullable();
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
