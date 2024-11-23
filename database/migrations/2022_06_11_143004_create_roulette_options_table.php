<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouletteOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roulette_options', function (Blueprint $table) {
            $table->id();
            $table->string('description')->comment('Descripción que se visualizará en la ruleta');
            $table->float('probability')->comment('Determina la probabilidad de que salga la opción en la ruleta');
            $table->string('background_color')->nullable()->comment('Determina si tiene un fondo con color hexadecimal');
            $table->string('background_image')->nullable()->comment('Determina si tiene un fondo con imagen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roulette_options');
    }
}
