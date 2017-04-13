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
            $table->foreign('caja_op_producto_id', 'caja_op_producto_id')
                    ->references('op_producto_id')
                    ->on('op_producto')
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
            $table->dropForeign('caja_op_producto_id');
        });
    }
}
