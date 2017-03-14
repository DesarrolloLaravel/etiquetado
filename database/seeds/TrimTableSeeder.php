<?php

use Illuminate\Database\Seeder;
use App\Models\Trim;

class TrimTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $trim = [];

        $trim [] = ['trim_nombre' => 'TD'];
        $trim [] = ['trim_nombre' => 'TE 3,5 cm'];

        Trim::insert($trim);
    }
}
