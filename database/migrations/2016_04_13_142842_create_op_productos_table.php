<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('op_producto', function (Blueprint $table) {
            
            $table->increments('op_producto_id');
            $table->unsignedInteger('op_producto_orden_id')->index();
            $table->unsignedInteger('op_producto_producto_id')->index();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('op_producto');
    }
}
