<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtiquetaMPsFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('etiqueta_mp', function(Blueprint $table)
        {
            $table->foreign('etiqueta_mp_lote_id', 'etiqueta_mp_lote_id')
                    ->references('lote_id')
                    ->on('lote')
                    ->onUpdate('NO ACTION')->onDelete('cascade');
        });

        Schema::table('etiqueta_mp', function(Blueprint $table)
        {
            $table->foreign('etiqueta_mp_producto_id', 'etiqueta_mp_producto_id')
                    ->references('producto_id')
                    ->on('producto')
                    ->onUpdate('NO ACTION')->onDelete('cascade');
        });

        Schema::table('etiqueta_mp', function(Blueprint $table)
        {
            $table->foreign('etiqueta_mp_posicion', 'etiqueta_mp_posicion')
                    ->references('camara_id')
                    ->on('camara')
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
    }
}
