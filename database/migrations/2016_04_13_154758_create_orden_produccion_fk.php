<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenProduccionFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('orden_produccion', function(Blueprint $table)
        {
            $table->foreign('orden_cliente_id', 'orden_cliente_id')
                    ->references('cliente_id')
                    ->on('cliente')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('orden_especie_id', 'orden_especie_id')
                    ->references('especie_id')
                    ->on('especie')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('orden_producto_id', 'orden_producto_id')
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
        Schema::table('orden_produccion', function(Blueprint $table)
        {
            $table->dropForeign('orden_cliente_id');
            $table->dropForeign('orden_especie_id');
            $table->dropForeign('orden_producto_id');
        });
    }
}
