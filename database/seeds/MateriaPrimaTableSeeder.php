<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\MateriaPrima;

class MateriaPrimaTableSeeder extends Seeder
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
        $mps = [];
        for ($i=0; $i < 10; $i++) { 
            # code...
            $mps [] = ['materia_prima_name' => $faker->name];
        }
        MateriaPrima::insert($mps);
    }
}
