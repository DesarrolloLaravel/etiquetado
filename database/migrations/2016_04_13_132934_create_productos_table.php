<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('producto', function (Blueprint $table) {
            
            $table->increments('producto_id');
            $table->string('producto_nombre')->nullable();
            $table->unsignedInteger('producto_especie_id')->index()->nullable();
            $table->unsignedInteger('producto_condicion_id')->nullable();
            $table->unsignedInteger('producto_formato_id')->index()->nullable();
            $table->unsignedInteger('producto_trim_id')->index()->nullable();
            $table->unsignedInteger('producto_calibre_id')->index()->nullable();
            $table->unsignedInteger('producto_calidad_id')->index()->nullable();
            $table->unsignedInteger('producto_variante_id')->nullable();
            $table->unsignedInteger('producto_v2_id')->nullable();
            $table->unsignedInteger('producto_envase1_id')->index()->nullable();
            $table->unsignedInteger('producto_envase2_id')->nullable();
            $table->string('producto_codigo')->nullable();
            $table->float('producto_peso')->nullable();

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
        Schema::drop('producto');
    }
}
