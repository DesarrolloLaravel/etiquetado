<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\Destino;

class DestinoTableSeeder extends Seeder
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
        $destinos = [];
        $destinos [] = ['destino_name' => 'Fillet TE 3.5 cm'];
        $destinos [] = ['destino_name' => 'Fillet TD'];
        Destino::insert($destinos);
    }
}
