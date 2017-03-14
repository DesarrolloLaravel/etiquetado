<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\OrdenProduccion;
use App\Models\Producto;
use App\Models\OrdenProduccionProducto;

class OpProductoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ordenes = OrdenProduccion::all();

        $op_producto = [];

        for ($i=0; $i < count($ordenes); $i++) { 
        	# code...
        	$productos = Producto::take(rand(1,10))->get();

        	for ($j=0; $j < count($productos); $j++) { 
        		# code...
        		$op_producto [] = ['op_producto_orden_id' => $ordenes[$i]->orden_id,
        							'op_producto_producto_id' => $productos[$j]->producto_id];
        	}
        }

        OrdenProduccionProducto::insert($op_producto);
    }
}
