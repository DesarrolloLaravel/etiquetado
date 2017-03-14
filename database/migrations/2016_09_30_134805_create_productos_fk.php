<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('producto', function(Blueprint $table)
        {
            $table->foreign('producto_calibre_id', 'producto_calibre_id')
                ->references('calibre_id')
                ->on('calibre')
                ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('producto_especie_id', 'producto_especie_id')
                ->references('especie_id')
                ->on('especie')
                ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('producto_formato_id', 'producto_formato_id')
                ->references('formato_id')
                ->on('formato')
                ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('producto_trim_id', 'producto_trim_id')
                ->references('trim_id')
                ->on('trim')
                ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('producto_calidad_id', 'producto_calidad_id')
                ->references('calidad_id')
                ->on('calidad')
                ->onUpdate('NO ACTION')->onDelete('cascade');

            $table->foreign('producto_envase1_id', 'producto_envase1_id')
                ->references('envase_id')
                ->on('envase')
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
        Schema::table('producto', function(Blueprint $table)
        {
            $table->dropForeign('producto_calibre_id');
            $table->dropForeign('producto_especie_id');
            $table->dropForeign('producto_formato_id');
            $table->dropForeign('producto_trim_id');
            $table->dropForeign('producto_calidad_id');
            $table->dropForeign('producto_envase1_id');
        });
    }
}
