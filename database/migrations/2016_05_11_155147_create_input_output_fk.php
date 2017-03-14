<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInputOutputFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('input_output', function(Blueprint $table)
        {
            $table->foreign('io_posicion_id', 'io_posicion_id')
                    ->references('posicion_id')
                    ->on('posicion')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('io_caja_id', 'io_caja_id')
                    ->references('caja_id')
                    ->on('caja')
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
        Schema::table('input_output', function(Blueprint $table)
        {
            $table->dropForeign('io_posicion_id');
            $table->dropForeign('io_caja_id');
        });
    }
}
