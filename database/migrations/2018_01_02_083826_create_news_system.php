<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $t) {
            $t->increments('id');
            $t->string('title');
            $t->string('url_slug');
            $t->unsignedInteger('airline_id');
            $t->unsignedInteger('user_id');
            $t->text('content');
            $t->string('cover_url');
            $t->timestamps();
            $t->boolean('published');
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
