<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->truncateTables(array(
            'users',
            'procesador',
            'elaborador',
            'especie',
            'productor',
            'materia_prima',
            'destino',
            'producto',
            'lote',
            'orden_produccion',
            'op_producto',
            'frigorifico',
            'camara',
            'posicion',
            'cliente'
        ));

        $this->call(UserSeeder::class);
        $this->call(ProcesadorTableSeeder::class);
        $this->call(ElaboradorTableSeeder::class);
        $this->call(EspecieTableSeeder::class);
        $this->call(ProductorTableSeeder::class);
        $this->call(MateriaPrimaTableSeeder::class);
        $this->call(DestinoTableSeeder::class);
        /*$this->call(LoteTableSeeder::class);
        $this->call(OrdenProduccionTableSeeder::class);
        $this->call(OpProductoTableSeeder::class);*/
        $this->call(FrigorificoTableSeeder::class);
        $this->call(CamaraTableSeeder::class);
        $this->call(PosicionTableSeeder::class);
        $this->call(CalibreTableSeeder::class);
        $this->call(CalidadTableSeeder::class);
        $this->call(FormatoTableSeeder::class);
        $this->call(TrimTableSeeder::class);
        $this->call(EnvaseTableSeeder::class);
        $this->call(ProductosTableSeeder::class);
        $this->call(ClienteTableSeeder::class);

        Model::reguard();
    }

    private function truncateTables(array $tables)
    {
        //se borran las claves foraneas para poder limpiar las tablas
        $this->checkForeignKeys(false);

        foreach ($tables as $table) {
            \DB::table($table)->truncate();
        }
        //las activamos para cargar correctamente los seeders
        $this->checkForeignKeys(true);
    }

    private function checkForeignKeys($check)
    {
        $check = $check ? '1' : '0';
        \DB::statement("SET FOREIGN_KEY_CHECKS = $check;");
    }
}
