<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLostResultToRouletteOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roulette_options', function (Blueprint $table) {
            $table->string('lost_result')->after('roulette_description')->comment('Muestra este texto luego de terminar de girar la ruleta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roulette_options', function (Blueprint $table) {
            $table->dropColumn('lost_result');
        });
    }
}
