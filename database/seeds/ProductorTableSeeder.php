<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\Productor;

class ProductorTableSeeder extends Seeder
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
        $productores = [];
        for ($i=0; $i < 1; $i++) {
            # code...
            $productores [] = ['productor_name' => 'AUSTRALIS'];
        }
        Productor::insert($productores);
    }
}
