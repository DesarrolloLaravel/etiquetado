<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDespachoLoteFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('despacho_lote', function(Blueprint $table)
        {
            $table->foreign('despacho_orden_id', 'despacho_orden_id')
                    ->references('orden_id')
                    ->on('orden_despacho')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('despacho_lote_id', 'despacho_lote_id')
                    ->references('lote_id')
                    ->on('lote')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('despacho_producto_id', 'despacho_producto_id')
                    ->references('producto_id')
                    ->on('producto')
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
        Schema::table('despacho_lote', function(Blueprint $table)
        {
            $table->dropForeign('despacho_orden_id');
            $table->dropForeign('despacho_lote_id');
            $table->dropForeign('despacho_producto_id');
        });
    }
}
