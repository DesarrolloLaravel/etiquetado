<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosicionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('posicion', function (Blueprint $table) {
            
            $table->increments('posicion_id');
            $table->string('posicion_nombre');
            $table->unsignedInteger('posicion_cajas');
            $table->unsignedInteger('posicion_camara_id')->index();

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
        Schema::drop('posicion');
    }
}
