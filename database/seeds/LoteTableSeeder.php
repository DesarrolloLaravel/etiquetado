<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\Lote;

class LoteTableSeeder extends Seeder
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

        $lotes = [];

        for ($i=0; $i < 10; $i++) { 
        	# code...
        	$lotes [] = ['lote_tipo_id' => rand(1,5),
                        'lote_users_id' => 1,
        				'lote_procesador_id' => rand(1,5),
        				'lote_elaborador_id' => rand(1,5),
                        'lote_mp_id' => rand(1,5),
        				'lote_productor_id' => rand(1,5),
        				'lote_especie_id' => rand(1,5),
        				'lote_variedad_id' => rand(1,5),
        				'lote_calidad_id' => rand(1,5),
        				'lote_fecha_documento' => \Carbon\Carbon::now()->format('Y-m-d'),
        				'lote_fecha_planta' => \Carbon\Carbon::now()->format('Y-m-d'),
        				'lote_fecha_expiracion' => \Carbon\Carbon::now()->format('Y-m-d'),
        				'lote_n_documento' => ($i+1),
        				'lote_kilos_declarado' => $faker->randomNumber(4),
                        'lote_kilos_recepcion' => $faker->randomNumber(4),
                        'lote_cajas_declarado' => $faker->randomNumber(4),
                        'lote_cajas_recepcion' => $faker->randomNumber(4),
        				'lote_year' => \Carbon\Carbon::now()->year,
                        'lote_djurada' => \Config::get('options.djurada')[rand(1,2)],
                        'lote_reestriccion' => \Config::get('options.reestriccion')[rand(1,2)],
                        'lote_observaciones' => $faker->text,
                        'lote_condicion' => \Config::get('options.conservacion')[rand(1,2)]];
        }

        Lote::insert($lotes);
    }
}
