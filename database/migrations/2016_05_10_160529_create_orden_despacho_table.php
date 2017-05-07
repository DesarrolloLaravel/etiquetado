<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenDespachoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('orden_despacho', function (Blueprint $table) {
            
            $table->increments('orden_id');
            $table->enum('orden_estado',array('PRE-DESPACHO','DESPACHO','DESPACHADO'));
            $table->date('orden_fecha');
            $table->string('orden_guia')->nullable();
            $table->unsignedInteger('orden_orden_produccion')->index();
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
        Schema::drop('orden_despacho');
    }
}
