<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoteFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('lote', function(Blueprint $table)
        {

            $table->foreign('lote_users_id', 'lote_users_id')
                    ->references('users_id')
                    ->on('users')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('lote_procesador_id', 'lote_procesador_id')
                    ->references('procesador_id')
                    ->on('procesador')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('lote_elaborador_id', 'lote_elaborador_id')
                    ->references('elaborador_id')
                    ->on('elaborador')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('lote_condicion_id', 'lote_condicion_id')
                    ->references('condicion_id')
                    ->on('condicion')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('lote_productor_id', 'lote_productor_id')
                    ->references('productor_id')
                    ->on('productor')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('lote_especie_id', 'lote_especie_id')
                    ->references('especie_id')
                    ->on('especie')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('lote_destino_id', 'lote_destino_id')
                    ->references('destino_id')
                    ->on('destino')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('lote_cliente_id', 'lote_cliente_id')
                    ->references('cliente_id')
                    ->on('cliente')
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
        Schema::table('lote', function(Blueprint $table)
        {
            $table->dropForeign('lote_users_id');
            $table->dropForeign('lote_procesador_id');
            $table->dropForeign('lote_elaborador_id');
            $table->dropForeign('lote_productor_id');
            $table->dropForeign('lote_especie_id');
            $table->dropForeign('lote_destino_id');
            $table->dropForeign('lote_cliente_id');
        });
    }
}
