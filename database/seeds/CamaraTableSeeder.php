<?php

use Illuminate\Database\Seeder;

use App\Models\Camara;
use App\Models\Frigorifico;

class CamaraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $frigo = Frigorifico::all();
        $camaras = [];
    	for ($i=0; $i < 2; $i++) { 
    		# code...
    		$camaras [] = ['camara_nombre' => 'Camara '.($i+1),
    					'camara_frigorifico_id' => $frigo[0]->frigorifico_id];
    	}
    	Camara::insert($camaras);
    	
    }
}
