<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenProduccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('orden_produccion', function (Blueprint $table) {
            
            $table->increments('orden_id');
            $table->string('orden_descripcion');
            $table->date('orden_fecha');
            $table->date('orden_fecha_inicio');
            $table->date('orden_fecha_compromiso');
            $table->unsignedInteger('orden_cliente_id');
            $table->unsignedInteger('orden_especie_id');
            $table->unsignedInteger('orden_producto_id');
            $table->unsignedInteger('orden_peso_estimado');
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
        Schema::drop('orden_produccion');
    }
}
