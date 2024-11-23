<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouletteStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roulette_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('roulette_options_id');
            $table->timestamps();

            // Indices
            $table->index('users_id');
            $table->index('roulette_options_id');

            // Foraneas
            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('roulette_options_id')->references('id')->on('roulette_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roulette_statistics');
    }
}
