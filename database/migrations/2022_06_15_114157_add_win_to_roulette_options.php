<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWinToRouletteOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roulette_options', function (Blueprint $table) {
            $table->boolean('win')->after('probability')->default(0)->comment('Determina si es una opción de victoria o perdida');
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
            $table->dropColumn('win');
        });
    }
}
