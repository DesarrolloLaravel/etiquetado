<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('lote', function (Blueprint $table) {
            
            $table->increments('lote_id');
            $table->unsignedInteger('lote_users_id')->index();
            $table->unsignedInteger('lote_tipo_id')->index();
            $table->unsignedInteger('lote_procesador_id')->index();
            $table->unsignedInteger('lote_elaborador_id')->index();
            $table->unsignedInteger('lote_mp_id')->index();
            $table->unsignedInteger('lote_productor_id')->index();
            $table->unsignedInteger('lote_especie_id')->index();
            $table->unsignedInteger('lote_destino_id')->index();
            $table->unsignedInteger('lote_calidad_id');
            $table->date('lote_fecha_documento');
            $table->date('lote_fecha_planta');
            $table->date('lote_fecha_expiracion');
            $table->string('lote_n_documento');
            $table->float('lote_kilos_declarado');
            $table->float('lote_kilos_recepcion');
            $table->float('lote_cajas_declarado');
            $table->float('lote_cajas_recepcion');
            $table->string('lote_year');
            $table->longText('lote_observaciones');

            $table->enum('lote_condicion', ['CONGELADO', 'FRESCO']);
            $table->enum('lote_djurada', ['SI', 'NO']);
            $table->enum('lote_reestriccion', ['SI','NO']);
            $table->enum('lote_produccion',['SI','NO'])->default('NO');
            
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
        Schema::drop('lote');
    }
}
