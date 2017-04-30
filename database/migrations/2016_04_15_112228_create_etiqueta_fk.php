<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtiquetaFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('etiqueta', function(Blueprint $table)
        {
            $table->foreign('etiqueta_caja_id', 'etiqueta_caja_id')
                    ->references('caja_id')
                    ->on('caja')
                    ->onUpdate('NO ACTION')->onDelete('cascade');
        });

        Schema::table('etiqueta', function(Blueprint $table)
        {
            $table->foreign('etiqueta_lote_id', 'etiqueta_lote_id')
                    ->references('lote_id')
                    ->on('lote')
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
        Schema::table('etiqueta', function(Blueprint $table)
        {
            $table->dropForeign('etiqueta_caja_id');
            $table->dropForeign('etiqueta_lote_id');
        });
    }
}
