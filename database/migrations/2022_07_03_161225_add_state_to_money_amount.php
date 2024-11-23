<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateToMoneyAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_amount', function (Blueprint $table) {
            $table->boolean('state')->after('attempts')->default(1)->comment('Determina si la cantidad puede mostrarse en la ruleta');
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
            $table->dropColumn('state');
        });
    }
}
