<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpProductosFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('op_producto', function(Blueprint $table)
        {
            $table->foreign('op_producto_orden_id', 'op_producto_orden_id')
                    ->references('orden_id')
                    ->on('orden_produccion')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('op_producto_producto_id', 'op_producto_producto_id')
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
        Schema::table('op_producto', function(Blueprint $table)
        {
            $table->dropForeign('op_producto_orden_id');
            $table->dropForeign('op_producto_producto_id');
        });
    }
}
