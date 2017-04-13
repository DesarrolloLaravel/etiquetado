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
            $table->foreign('orden_lote_id', 'orden_lote_id')
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
        Schema::table('orden_produccion', function(Blueprint $table)
        {
            $table->dropForeign('orden_lote_id');
        });
    }
}
