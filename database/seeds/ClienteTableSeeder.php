<?php

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $clientes = [];

        $clientes [] = ['cliente_nombre' => 'EVERFISH'];

        Cliente::insert($clientes);
    }
}
