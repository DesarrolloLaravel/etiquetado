<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCalibre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibre', function (Blueprint $table) {
            //
            $table->foreign('calibre_unidad_medida_id', 'calibre_unidad_medida_id')
                ->references('unidad_medida_id')
                ->on('unidad_medida')
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
        Schema::table('calibre', function (Blueprint $table) {
            //
        });
    }
}
