<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEspecieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('especie', function (Blueprint $table) {
            
            $table->increments('especie_id');
            $table->string('especie_name');
            $table->string('especie_comercial_name');
            $table->string('especie_abbreviation');

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
        Schema::drop('especie');
    }
}
