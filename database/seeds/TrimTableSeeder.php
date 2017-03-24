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

        $trim [] = ['trim_name' => 'TD'];
        $trim [] = ['trim_name' => 'TE 3,5 cm'];

        Trim::insert($trim);
    }
}
