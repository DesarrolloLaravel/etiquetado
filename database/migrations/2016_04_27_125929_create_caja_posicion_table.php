<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaPosicionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //tabla que guarda el stock actual de las cajas en las distintas posiciones
        //de las camaras
        Schema::create('caja_posicion', function (Blueprint $table) {
            
            $table->increments('caja_posicion_id');
            $table->unsignedInteger('caja_posicion_caja_id')->index();
            $table->unsignedInteger('caja_posicion_posicion_id')->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('caja_posicion');
    }
}
