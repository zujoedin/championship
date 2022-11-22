<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id_1');
            $table->foreign('team_id_1')->references('id')->on('teams');
            $table->unsignedBigInteger('team_id_2');
            $table->foreign('team_id_2')->references('id')->on('teams');
            $table->integer('score_1');
            $table->integer('score_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
