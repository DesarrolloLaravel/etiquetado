<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\FakerExtend\RutGenerator;
use App\Models\Elaborador;

class ElaboradorTableSeeder extends Seeder
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
        $elaboradores = [];
        $elaboradores [] = ['elaborador_name' => 'AGUAS CLARAS',
            'elaborador_rut' => RutGenerator::validarRut(RutGenerator::generar())];

        $elaboradores [] = ['elaborador_name' => 'AQUACHILE',
            'elaborador_rut' => RutGenerator::validarRut(RutGenerator::generar())];

        $elaboradores [] = ['elaborador_name' => 'PRIMAR',
            'elaborador_rut' => RutGenerator::validarRut(RutGenerator::generar())];

        $elaboradores [] = ['elaborador_name' => 'PACIFICSTAR',
            'elaborador_rut' => RutGenerator::validarRut(RutGenerator::generar())];

        $elaboradores [] = ['elaborador_name' => 'FITZ ROY',
            'elaborador_rut' => RutGenerator::validarRut(RutGenerator::generar())];

        Elaborador::insert($elaboradores);
    }
}
