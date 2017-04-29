<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('caja', function(Blueprint $table)
        {
            $table->foreign('caja_ot_producto_id', 'caja_ot_producto_id')
                    ->references('orden_trabajo_id')
                    ->on('orden_trabajo')
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
        Schema::table('caja', function(Blueprint $table)
        {
            $table->dropForeign('caja_ot_producto_id');
        });
    }
}
