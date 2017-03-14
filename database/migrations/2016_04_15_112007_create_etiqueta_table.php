<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtiquetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('etiqueta', function (Blueprint $table) {
            
            $table->increments('etiqueta_id');
            $table->unsignedInteger('etiqueta_caja_id')->index();
            $table->string('etiqueta_barcode');
            $table->date('etiqueta_fecha');
            $table->enum('etiqueta_estado', ['RECEPCIONADA', 'NO RECEPCIONADA', 'ANULADA'])->default('NO RECEPCIONADA');

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
        Schema::drop('etiqueta');
    }
}
