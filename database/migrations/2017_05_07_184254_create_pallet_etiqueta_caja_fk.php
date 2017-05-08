<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalletEtiquetaCajaFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pallet_etiqueta_caja', function(Blueprint $table)
        {
            $table->foreign('pec_pallet_id', 'pec_pallet_id')
                    ->references('pallet_pt_id')
                    ->on('pallet_pt')
                    ->onUpdate('NO ACTION')->onDelete('cascade');

        });

        Schema::table('pallet_etiqueta_caja', function(Blueprint $table)
        {
            $table->foreign('pec_etiqueta_id', 'pec_etiqueta_id')
                    ->references('etiqueta_id')
                    ->on('etiqueta')
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
    }
}
