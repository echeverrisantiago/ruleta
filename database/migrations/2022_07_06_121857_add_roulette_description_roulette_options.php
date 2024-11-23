<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRouletteDescriptionRouletteOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roulette_options', function (Blueprint $table) {
            $table->string('roulette_description')->after('background_image')->comment('Descripción de la opción de la ruleta');
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
            $table->dropColumn('roulette_description');
        });
    }
}
