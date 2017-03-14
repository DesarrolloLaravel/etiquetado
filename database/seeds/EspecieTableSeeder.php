<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\Especie;

class EspecieTableSeeder extends Seeder
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
        $especies = [];
        for ($i=0; $i < 1; $i++) {
            # code...
            $especies [] = ['especie_name' => 'Salmo salar',
                'especie_comercial_name' => 'ATLANTIC SALMON',
                'especie_abbreviation' => 'ATL'];
        }
        Especie::insert($especies);
    }
}
