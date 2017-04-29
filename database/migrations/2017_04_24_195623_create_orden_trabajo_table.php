<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenTrabajoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('orden_trabajo', function (Blueprint $table){

            $table->increments('orden_trabajo_id');
            $table->unsignedInteger('orden_trabajo_orden_produccion')->index();
            $table->unsignedInteger('orden_trabajo_producto')->index();
            $table->unsignedInteger('orden_trabajo_especie')->index();
            $table->date('orden_trabajo_fecha');
            $table->unsignedInteger('orden_trabajo_peso_total');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('orden_trabajo_orden_produccion', 'orden_trabajo_orden_produccion')
                    ->references('orden_id')
                    ->on('orden_produccion')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('orden_trabajo_producto', 'orden_trabajo_producto')
                    ->references('producto_id')
                    ->on('producto')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

             $table->foreign('orden_trabajo_especie', 'orden_trabajo_especie')
                    ->references('especie_id')
                    ->on('especie')
                    ->onUpdate('NO ACTION')->onDelete('cascade');
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
        Schema::table('orden_trabajo', function(Blueprint $table)
        {
            $table->dropForeign('orden_trabajo_orden_produccion');
            $table->dropForeign('orden_trabajo_producto');
            $table->dropForeign('orden_trabajo_especie');
        });
    }
}
