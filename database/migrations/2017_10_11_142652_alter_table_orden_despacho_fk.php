<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrdenDespachoFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('orden_despacho', function(Blueprint $table)
        {
            //$table->unsignedInteger('orden_cliente_id')->index()->change();
            $table->foreign('orden_cliente_id', 'orden_despacho_cliente_id')
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
        Schema::table('orden_despacho', function(Blueprint $table)
        {
            $table->dropForeign('orden_despacho_cliente_id');
        });
    }
}
