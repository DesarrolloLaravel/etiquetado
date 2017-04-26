<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ot_producto', function (Blueprint $table){

            $table->increments('ot_producto_id');
            $table->unsignedInteger('ot_producto_orden_trabajo')->index();
            $table->string('ot_producto_codigo_pallet')->unique();
            $table->unsignedInteger('ot_producto_peso');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ot_producto_orden_trabajo', 'ot_producto_orden_trabajo')
                    ->references('orden_trabajo_id')
                    ->on('order_trabajo')
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
        Schema::table('ot_producto', function(Blueprint $table)
        {
            $table->dropForeign('ot_producto_orden_trabajo');
        });
    }
}
