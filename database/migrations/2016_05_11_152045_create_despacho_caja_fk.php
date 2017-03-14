<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDespachoCajaFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('despacho_caja', function(Blueprint $table)
        {
            $table->foreign('despacho_caja_caja_id', 'despacho_caja_caja_id')
                    ->references('caja_id')
                    ->on('caja')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('despacho_caja_despacho_lote_id', 'despacho_caja_despacho_lote_id')
                    ->references('despacho_id')
                    ->on('despacho_lote')
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
        Schema::table('despacho_caja', function(Blueprint $table)
        {
            $table->dropForeign('despacho_caja_caja_id');
            $table->dropForeign('despacho_caja_despacho_lote_id');
        });
    }
}
