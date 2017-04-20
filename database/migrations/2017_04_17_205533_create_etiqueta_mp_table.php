<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtiquetaMPTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etiqueta_mp', function (Blueprint $table) {
            $table->increments('etiqueta_mp_id');
            $table->unsignedInteger('etiqueta_mp_lote_id')->index();
            $table->unsignedInteger('etiqueta_mp_producto_id')->index();
            $table->date('etiqueta_mp_fecha');
            $table->string('etiqueta_mp_barcode');
            $table->float('etiqueta_mp_peso');
            $table->float('etiqueta_mp_cantidad_cajas');
            $table->unsignedInteger('etiqueta_mp_posicion')->index()->nullable();

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
        Schema::drop('etiqueta_mp');
    }
}
