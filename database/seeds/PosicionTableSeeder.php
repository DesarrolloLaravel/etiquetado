<?php

use Illuminate\Database\Seeder;

use App\Models\Posicion;
use App\Models\Camara;

class PosicionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	$camaras = Camara::all();
    	$pos = [];
    	foreach ($camaras as $cam) {
    		# code...
    		$pos [] = ['posicion_nombre' => 'Posicion 1',
                        'posicion_cajas' => 0,
                        'posicion_camara_id' => $cam->camara_id];
    	}
        Posicion::insert($pos);
    }
}
