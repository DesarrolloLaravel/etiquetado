<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCamaraFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('camara', function(Blueprint $table)
        {
            $table->foreign('camara_frigorifico_id', 'camara_frigorifico_id')
                    ->references('frigorifico_id')
                    ->on('frigorifico')
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
        Schema::table('camara', function(Blueprint $table)
        {
            $table->dropForeign('camara_frigorifico_id');
        });
    }
}
