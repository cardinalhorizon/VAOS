<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_progress', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task');
            $table->string('slug');
            $table->string('description');
            $table->integer('totalitems');
            $table->integer('itemscompleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('job_progress');
    }
}
