<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeepTryingToRouletteOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roulette_options', function (Blueprint $table) {
            $table->boolean('keep_trying')->after('win')->default(0)->comment('Determina si es una opción para seguir intentando o no');
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
            $table->dropColumn('keep_trying');
        });
    }
}
