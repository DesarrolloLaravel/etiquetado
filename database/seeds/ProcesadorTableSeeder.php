<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\FakerExtend\RutGenerator as RutGenerator;
use App\Models\Procesador;

class ProcesadorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        $procesadores = [];
        for ($i=0; $i < 1; $i++) {
        	# code...
            $procesadores [] = ['procesador_name' => 'ACUAFOOD',
                                'procesador_rut' => RutGenerator::validarRut(RutGenerator::generar())];
        }
        Procesador::insert($procesadores);
    }
}
