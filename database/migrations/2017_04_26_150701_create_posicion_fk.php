<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosicionFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('posicion', function(Blueprint $table)
        {
            $table->foreign('posicion_camara_id', 'posicion_camara_id')
                    ->references('camara_id')
                    ->on('camara')
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
        Schema::table('posicion', function(Blueprint $table)
        {
            $table->dropForeign('posicion_camara_id');
        });
    }
}
