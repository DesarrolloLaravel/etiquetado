<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDespachoLoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //tabla que guarda el detalle de lotes sobre los cuales se hara el despacho
        Schema::create('despacho_lote', function (Blueprint $table) {
            
            $table->increments('despacho_id');
            $table->unsignedInteger('despacho_orden_id')->index();
            $table->unsignedInteger('despacho_lote_id')->index();
            $table->unsignedInteger('despacho_producto_id')->index();
            $table->Integer('despacho_cajas_plan');
            $table->float('despacho_kilos_plan');
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
        Schema::drop('despacho_lote');
    }
}
