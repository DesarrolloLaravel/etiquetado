<?php

use Illuminate\Database\Seeder;
use App\Models\Formato;

class FormatoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $formato = [];

        $formato [] = ['formato_nombre' => 'Fillet',
            'formato_abreviatura' => 'Fillet'];

        $formato [] = ['formato_nombre' => 'Hon',
            'formato_abreviatura' => 'Hon'];

        Formato::insert($formato);
    }
}
