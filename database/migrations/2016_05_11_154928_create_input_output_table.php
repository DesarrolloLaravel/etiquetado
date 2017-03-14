<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInputOutputTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //tabla que guarda los movimientos de stock
        Schema::create('input_output', function (Blueprint $table) {
            
            $table->increments('io_id');
            $table->unsignedInteger('io_posicion_id')->index();
            $table->unsignedInteger('io_caja_id')->index();
            $table->enum('io_tipo',['ENTRADA', 'SALIDA']);
            $table->enum('io_proceso',['PRODUCCION', 'DESPACHO']);
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
        Schema::drop('input_output');
    }
}
