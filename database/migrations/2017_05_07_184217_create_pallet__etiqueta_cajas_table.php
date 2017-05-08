<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalletEtiquetaCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallet_etiqueta_caja', function (Blueprint $table) {
            $table->increments('pec_id');
            $table->unsignedInteger('pec_etiqueta_id')->index();
            $table->unsignedInteger('pec_pallet_id')->index();
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
        Schema::drop('pallet_etiqueta_caja');
    }
}
