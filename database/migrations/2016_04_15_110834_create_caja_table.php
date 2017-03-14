<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('caja', function (Blueprint $table) {
            
            $table->increments('caja_id');
            $table->unsignedInteger('caja_op_producto_id')->index();
            $table->float('caja_peso_real');
            $table->float('caja_glaseado');
            $table->float('caja_peso_bruto');
            $table->float('caja_unidades');
            //$table->enum('caja_estado',["OK","NOK"])->default("NOK");

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
        Schema::drop('caja');
    }
}
