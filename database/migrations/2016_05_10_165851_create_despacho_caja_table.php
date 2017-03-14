<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDespachoCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //tabal que guarda el detalle de cajas que seran despachadas para cada lote
        Schema::create('despacho_caja', function (Blueprint $table) {
            
            $table->increments('despacho_caja_id');
            $table->unsignedInteger('despacho_caja_caja_id')->index();
            $table->unsignedInteger('despacho_caja_despacho_lote_id')->index();
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
        Schema::drop('despacho_caja');
    }
}
