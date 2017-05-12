<?php

use Illuminate\Database\Seeder;
use App\Models\Condicion;

class CondicionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $condicion = [];

        $condicion [] = ['condicion_name' => 'Congelado'];
        $condicion [] = ['condicion_name' => 'Fresco'];
       
        Condicion::insert($condicion);
    }
}
