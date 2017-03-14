<?php

use Illuminate\Database\Seeder;
use App\Models\Calidad;

class CalidadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $calidad = [];

        $calidad [] = ['calidad_nombre' => 'IND A'];
        $calidad [] = ['calidad_nombre' => 'IND B'];
        $calidad [] = ['calidad_nombre' => 'IND M'];
        $calidad [] = ['calidad_nombre' => 'IND S'];
        $calidad [] = ['calidad_nombre' => 'IND I'];
        $calidad [] = ['calidad_nombre' => 'Superior'];
        $calidad [] = ['calidad_nombre' => 'M'];
        $calidad [] = ['calidad_nombre' => 'Industrial'];

        Calidad::insert($calidad);
    }
}
