<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\OrdenProduccion\CreateRequest;
use App\Http\Requests\OrdenProduccion\UpdateRequest;

use App\Models\OrdenProduccion;
use App\Models\Producto;
use App\Models\Especie;
use App\Models\OrdenProduccionProducto;


class OrdenProduccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function cargar_producto(Request $request)
    {
        //
        

        $productos = Producto::orderBy('producto_nombre','ASC')
                ->where('producto_especie_id',$request->especie_id)
                ->lists('producto_nombre','producto_id')
                ->all();

        return $productos; 
    }

    public function kilos_def(Request $request)
    {
        //
        $kilos = Producto::select('producto_peso')->where('producto_id',$request->product_id)->first();
        
        return $kilos;
    }

    public function index(Request $request)
    {
        //
        if($request->ajax())
        {
            $ordenes = OrdenProduccion::with('Cliente')->get();

            if($ordenes->count() == 0)
            {
                return '{"data":[]}'; 
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($ordenes as $orden) {
                //completo el json
                $dt_json .= '["","'
                                .$orden->orden_id.'","'
                                .$orden->cliente->cliente_nombre.'","'
                                .\Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_fecha)->format('d-m-Y').'"],';
            }
            //elimino la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);
            //se cierra el json
            $dt_json.= "] }";
            //envio respuesta al cliente
            return $dt_json;
        }
        else
        {
            return view('admin.orden_produccion.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if($request->ajax())
        {

            $clientes = [''=>'Ninguno'] +
                Cliente::orderBy('cliente_nombre', 'ASC')
                    ->get()
                    ->lists('cliente_nombre','cliente_id')
                    ->all();

            $especies =[''=>'Ninguno'] +
                Especie::orderBy('especie_name','ASC')
                    ->get()
                    ->lists('especie_name','especie_id')
                    ->all();
            
            $productos =[''=>'Ninguno'];

            $proxima_orden = OrdenProduccion::all()->max('orden_id')+1;

            $orden_fecha = \Carbon\Carbon::now()->format('d-m-Y');
            $orden_fecha_inicio = \Carbon\Carbon::now()->format('d-m-Y');
            $orden_fecha_compromiso = \Carbon\Carbon::now()->format('d-m-Y');

            $view = \View::make('admin.orden_produccion.fields')
                    ->with('clientes', $clientes)
                    ->with('proxima_orden', $proxima_orden)
                    ->with('orden_fecha', $orden_fecha)
                    ->with('orden_fecha_inicio', $orden_fecha_inicio)
                    ->with('orden_fecha_compromiso', $orden_fecha_compromiso)
                    ->with('especies',$especies)
                    ->with('productos',$productos);

            $sections = $view->renderSections();

            return response()->json(["estado" => "ok","section" => $sections['contentPanel']]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        //

        $orden_fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->orden_fecha);
        $orden_fecha_inicio = \Carbon\Carbon::createFromFormat('d-m-Y', $request->orden_fecha_inicio);
        $orden_fecha_compromiso = \Carbon\Carbon::createFromFormat('d-m-Y', $request->orden_fecha_compromiso);
       
        $info = array(
            'orden_id'          => $request->orden_id,
            'orden_descripcion'     => $request->orden_descripcion,
            'orden_fecha'           => $orden_fecha,
            'orden_fecha_inicio'    => $orden_fecha_inicio,
            'orden_fecha_compromiso'=> $orden_fecha_compromiso,
            'orden_cliente_id'      => $request->orden_cliente_id
        );

       
        $orden = OrdenProduccion::create($info);

        $op = new OrdenProduccionProducto;     

        $prod = (array) $request->productos;
        $peso = (array) $request->kilos;

        foreach ( $prod as $p ) {
        
            $key = array_search($p,$prod);
            $op->op_producto_orden_id = $orden->orden_id;            
            $op->op_producto_producto_id = $p;
            $op->op_producto_kilos_declarados = $peso[$key];
            $op->save();
        }

        //envio respuesta al cliente
        return response()->json([
            "ok"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $orden = OrdenProduccion::findOrFail($request->orden_id);

        if($request->ajax())
        {
            $resp = [];

            $resp['orden_id'] = $orden->orden_id;
            $resp['orden_descripcion'] = $orden->orden_descripcion;
            $resp['orden_fecha'] = $orden->orden_fecha;

            $producto = OrdenProduccionProducto::where('op_producto_orden_id',$request->orden_id)
            ->get('op_producto_id');


            $resp['orden_productos'] = $producto;

            return response()->json($resp);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        $orden = OrdenProduccion::findOrFail($request->orden_id);

        if($request->ajax())
        {
            $productos = [''=>'Ninguno'];

            $clientes = [''=>'Ninguno'] +
                Cliente::orderBy('cliente_nombre', 'ASC')
                    ->get()
                    ->lists('cliente_nombre','cliente_id')
                    ->all();

             $especies =[''=>'Ninguno'] +
                Especie::orderBy('especie_name','ASC')
                    ->get()
                    ->lists('especie_name','especie_id')
                    ->all();

            $op = OrdenProduccionProducto::where('op_producto_orden_id',$request->orden_id)
                ->getAll()
                ->lists('op_producto_producto_id','op_producto_kilos_declarados');


            $orden_fecha = \Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_fecha)->format('d-m-Y');
            $orden_fecha_inicio = \Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_fecha_inicio)->format('d-m-Y');
            $orden_fecha_compromiso = \Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_fecha_compromiso)->format('d-m-Y');

            $view = \View::make('admin.orden_produccion.fields')
                    ->with('clientes', $clientes)
                    ->with('proxima_orden', $orden->orden_id)
                    ->with('orden_fecha', $orden_fecha)
                    ->with('orden_fecha_inicio', $orden_fecha_inicio)
                    ->with('orden_fecha_compromiso', $orden_fecha_compromiso)
                    ->with('especies',$especies)
                    ->with('productos',$op);

            $sections = $view->renderSections();

            return response()->json(["estado" => "ok", "orden" => $orden, "section" => $sections['contentPanel']]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        //
        if($request->ajax())
        {
            $orden = OrdenProduccion::find($request->orden_number);

            $orden_fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->orden_fecha);
            $orden_fecha_inicio = \Carbon\Carbon::createFromFormat('d-m-Y', $request->orden_fecha_inicio);
            $orden_fecha_compromiso = \Carbon\Carbon::createFromFormat('d-m-Y', $request->orden_fecha_compromiso);

            //dd(explode(delimiter, string)$request->productos[1]);

            $info = array(
                'orden_lote_id'         => $request->orden_lote_id,
                'orden_number'          => $request->orden_number,
                'orden_descripcion'     => $request->orden_descripcion,
                'orden_fecha'           => $orden_fecha,
                'orden_fecha_inicio'    => $orden_fecha_inicio,
                'orden_fecha_compromiso'=> $orden_fecha_compromiso,
                'orden_cliente_id'      => $request->orden_cliente_id,
                'orden_ciudad_id'       => $request->orden_ciudad_id,
                'orden_provincia_id'    => $request->orden_provincia_id
            );

            \DB::beginTransaction();

            try{
                $orden->fill($info);
                $orden->save();
                $orden->productos()->detach();
                $orden->productos()->attach(explode(",", $request->productos));
            }
            catch ( Exception $e ){
                \DB::rollback();
            }

            \DB::commit();
            //envio respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        dd($id);
    }
}
