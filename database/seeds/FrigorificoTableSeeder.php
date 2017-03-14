<?php

use Illuminate\Database\Seeder;

use App\Models\Frigorifico;

class FrigorificoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $frigorifico = ['frigorifico_nombre' => 'Frigorifico 1'];
        Frigorifico::insert($frigorifico);
    }
}
