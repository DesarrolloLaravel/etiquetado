<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaPosicionFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('caja_posicion', function(Blueprint $table)
        {
            $table->foreign('caja_posicion_caja_id', 'caja_posicion_caja_id')
                    ->references('caja_id')
                    ->on('caja')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('caja_posicion_posicion_id', 'caja_posicion_posicion_id')
                    ->references('posicion_id')
                    ->on('posicion')
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
        Schema::table('caja_posicion', function(Blueprint $table)
        {
            $table->dropForeign('caja_posicion_caja_id');
            $table->dropForeign('caja_posicion_posicion_id');
        });
    }
}
