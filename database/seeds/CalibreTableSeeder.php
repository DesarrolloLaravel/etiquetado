<?php

use Illuminate\Database\Seeder;
use App\Models\Calibre;

class CalibreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $calibre = [];

        $calibre [] = ['calibre_nombre' => '450-900 g'];
        $calibre [] = ['calibre_nombre' => '900-1350 g'];
        $calibre [] = ['calibre_nombre' => '1350-1800 g'];
        $calibre [] = ['calibre_nombre' => 'S/C'];

        Calibre::insert($calibre);
    }
}
