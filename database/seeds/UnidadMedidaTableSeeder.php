<?php

use Illuminate\Database\Seeder;

class UnidadMedidaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \DB::table('unidad_medida')->insert(array(
            'unidad_medida_nombre'		=> 'Kilos',
            'unidad_medida_abreviacion'		=> 'Kg',
        ));

        \DB::table('unidad_medida')->insert(array(
            'unidad_medida_nombre'		=> 'Gramos',
            'unidad_medida_abreviacion'		=> 'Gr',
        ));

        \DB::table('unidad_medida')->insert(array(
            'unidad_medida_nombre'      => 'Sin Medida',
            'unidad_medida_abreviacion'     => '.',
        ));

    }
}
