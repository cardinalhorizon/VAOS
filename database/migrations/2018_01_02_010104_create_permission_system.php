<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('permissions', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->string('key');
            $t->string('verb')->nullable();
            $t->string('route')->nullable();
            $t->unsignedInteger('airline_id')->nullable();
            $t->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $t->text('description')->nullable();
        });
        Schema::create('permission_groups', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->unsignedInteger('airline_id')->nullable();
            $t->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $t->text('description')->nullable();
        });

        /*
         * Pivot Tables
         */
        Schema::create('permission_permission_group', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('permission_id');
            $t->unsignedInteger('permission_group_id');
            $t->integer('assign_power');
            $t->integer('modify_power');
            $t->integer('remove_power');

            $t->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $t->foreign('permission_group_id')->references('id')->on('permission_groups')->onDelete('cascade');
        });
        Schema::create('permission_user', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('user_id');
            $t->unsignedInteger('permission_id');
            $t->unsignedInteger('airline_id')->nullable();

            $t->integer('assign_power');
            $t->integer('modify_power');
            $t->integer('remove_power');

            $t->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $t->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('permission_group_user', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('user_id');
            $t->unsignedInteger('permission_group_id');
            $t->unsignedInteger('airline_id')->nullable();
            $t->integer('assign_power');
            $t->integer('modify_power');
            $t->integer('remove_power');

            $t->foreign('permission_group_id')->references('id')->on('permission_groups')->onDelete('cascade');
            $t->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
