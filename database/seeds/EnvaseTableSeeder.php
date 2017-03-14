<?php

use Illuminate\Database\Seeder;
use App\Models\Envase;

class EnvaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $envases = [];

        $envases [] = ['envase_nombre' => 'Caja 10 kg',
            'envase_capacidad' => '10kg'];

        $envases [] = ['envase_nombre' => 'Caja 15 kg',
            'envase_capacidad' => '15 kg'];

        Envase::insert($envases);
    }
}
