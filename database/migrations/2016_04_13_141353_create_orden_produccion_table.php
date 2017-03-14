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
            $table->unsignedInteger('orden_lote_id')->index();
            $table->string('orden_descripcion');
            $table->date('orden_fecha');
            $table->date('orden_fecha_inicio');
            $table->date('orden_fecha_compromiso');
            $table->unsignedInteger('orden_cliente_id');
            $table->unsignedInteger('orden_ciudad_id');
            $table->unsignedInteger('orden_provincia_id');
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
