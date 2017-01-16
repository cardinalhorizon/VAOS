<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->boolean('progress');
            $table->integer('minhrs');
            // ACCESS PERMISSIONS AND AIRCRAFT PIVOT TABLES
            $table->timestamps();
        });
        Schema::create('group_user', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            $table->boolean('primary');
            $table->timestamps();
        });
        Schema::create('user_group_aircraft_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->nullable();
            $table->integer('aircraft_group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('set null');
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
        Schema::drop('user_group_aircraft_groups');
        Schema::drop('groups');
        Schema::drop('user_group');
    }
}
