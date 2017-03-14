<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\OrdenProduccion;
use App\Models\Lote;

class OrdenProduccionTableSeeder extends Seeder
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
        
        $lotes = Lote::all();

        $op = [];
        for ($i=0; $i < count($lotes); $i++) { 
            # code...
            $op [] = [  'orden_lote_id' => $lotes[$i]->lote_id,
            			'orden_descripcion' => $faker->text(50),
            			'orden_fecha' => $faker->date,
            			'orden_fecha_inicio' => $faker->date,
            			'orden_fecha_compromiso' => $faker->date,
            			'orden_cliente_id' => rand(1,5),
            			'orden_ciudad_id' => rand(1,5),
            			'orden_provincia_id' => rand(1,5)];
        }
        OrdenProduccion::insert($op);
    }
}
