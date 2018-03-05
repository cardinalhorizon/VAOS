<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeRatingSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_ratings', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('airline_id');
            $t->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $t->string('name');
            $t->string('code'); // Abbreviation
            $t->text('description')->nullable();
            $t->string('icon_url')->nullable();
            $t->timestamps();
        });
        Schema::create('type_rating_user', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('user_id');
            $t->unsignedInteger('type_rating_id');
            $t->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $t->foreign('type_rating_id')->references('id')->on('type_ratings')->onDelete('cascade');
            $t->timestamps();
        });
        Schema::create('aircraft_group_type_rating', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('aircraft_group_id');
            $t->unsignedInteger('type_rating_id');
            $t->foreign('aircraft_group_id')->references('id')->on('aircraft_groups')->onDelete('cascade');
            $t->foreign('type_rating_id')->references('id')->on('type_ratings')->onDelete('cascade');
        });
        Schema::create('aircraft_type_rating', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('aircraft_id');
            $t->unsignedInteger('type_rating_id');
            $t->foreign('aircraft_id')->references('id')->on('aircraft')->onDelete('cascade');
            $t->foreign('type_rating_id')->references('id')->on('type_ratings')->onDelete('cascade');
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
