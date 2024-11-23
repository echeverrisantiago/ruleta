<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoneyAmountIdToRouletteStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roulette_statistics', function (Blueprint $table) {
            $table->unsignedBigInteger('money_amount_id')->after('roulette_options_id')->comment('Cantidad monetaria por la cual participa el cliente');

            $table->index('money_amount_id');

            $table->foreign('money_amount_id')->references('id')->on('money_amount')->onDelete('cascade');
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
            $table->dropColumn('roulette_statistics');
        });
    }
}
