<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttemptsToMoneyAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_amount', function (Blueprint $table) {
            $table->integer('attempts')->after('quantity')->unsigned()->comment('Cantida de intentos disponibles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_amount', function (Blueprint $table) {
            $table->dropColumn('attempts');
        });
    }
}
