<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ot_producto', function (Blueprint $table){

            $table->increments('ot_producto_id');
            $table->unsignedInteger('ot_producto_orden_trabajo')->index();
            $table->unsignedInteger('ot_producto_etiqueta_pallet')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ot_producto_orden_trabajo', 'ot_producto_orden_trabajo')
                    ->references('orden_trabajo_id')
                    ->on('orden_trabajo')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('ot_producto_etiqueta_pallet', 'ot_producto_etiqueta_pallet')
                    ->references('etiqueta_mp_id')
                    ->on('etiqueta_mp')
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
        Schema::table('ot_producto', function(Blueprint $table)
        {
            $table->dropForeign('ot_producto_orden_trabajo');
            $table->dropForeign('ot_producto_etiqueta_pallet');
        });
    }
}
