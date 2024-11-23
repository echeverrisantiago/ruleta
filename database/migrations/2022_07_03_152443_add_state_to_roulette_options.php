<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateToRouletteOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roulette_options', function (Blueprint $table) {
            $table->boolean('state')->after('keep_trying')->default(1)->comment('Determina si la opción puede mostrarse en la ruleta');
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
            $table->dropColumn('state');
        });
    }
}
