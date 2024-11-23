<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeToRouletteStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roulette_statistics', function (Blueprint $table) {
            $table->string('code')->after('money_amount_id')->comment('Codigo para identificar si pertenece o no al mismo giro de la ruleta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roulette_statistics', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
