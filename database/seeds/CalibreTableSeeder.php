<?php

use Illuminate\Database\Seeder;
use App\Models\Calibre;
use Faker\Factory as Faker;

class CalibreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UnidadMedidaTableSeeder::class);
        //
        $faker = Faker::create();


        $calibre = [];

        $calibre [] = ['calibre_nombre' => '450-900',
                        'calibre_unidad_medida_id' => '2'];
        $calibre [] = ['calibre_nombre' => '900-1350',
                        'calibre_unidad_medida_id' => '2'];
        $calibre [] = ['calibre_nombre' => '1350-1800',
                        'calibre_unidad_medida_id' => '2'];
        $calibre [] = ['calibre_nombre' => 'S/C',
                        'calibre_unidad_medida_id' => '3'];

        Calibre::insert($calibre);
    }
}
