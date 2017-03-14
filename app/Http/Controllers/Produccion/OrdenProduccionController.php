<?php

namespace App\Http\Controllers\Produccion;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\OrdenProduccion\CreateRequest;

use App\Models\OrdenProduccion;
use App\Models\Producto;
use App\Models\Lote;

class OrdenProduccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $ordenes = OrdenProduccion::all();

        if($request->ajax())
        {
            if($request->q == "etiqueta")
            {
                $ordenes = Lote::find($request->lote_id)->orden_produccion;
                        
                if($ordenes->count() == 0)
                {
                    return '{"data":[]}';
                }
            }
            
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($ordenes as $orden) {
                //completo el json
                $dt_json .= '["","'
                                .$orden->orden_id.'","'
                                .$orden->lote->lote_id.'","'
                                .\Config::get('options.cliente')[$orden->orden_cliente_id].'","'
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
            return view('produccion.orden_produccion.index', compact('ordenes'));
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
            $lotes = [''=>'Ninguno'] + 
                        Lote::where('lote_produccion', 'SI')
                            ->get()
                            ->lists('lote_id', 'lote_id')
                            ->all();

            $productos = [''=>'Ninguno'] + 
                        Producto::orderBy('producto_nombre', 'ASC')
                        ->get()
                        ->lists('producto_nombre','producto_id')
                        ->all();

            $proxima_orden = OrdenProduccion::all()->max('orden_id')+1;

            $view = \View::make('produccion.orden_produccion.fields')
                    ->with('productos', $productos)
                    ->with('lotes', $lotes)
                    ->with('proxima_orden', $proxima_orden);

            $sections = $view->renderSections();

            return response()->json(["ok",$sections['contentPanel']]);
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
            $orden = OrdenProduccion::create($info);
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
            $resp['orden_lote_id'] = $orden->lote->lote_id;
            $resp['orden_descripcion'] = $orden->orden_descripcion;
            $resp['orden_fecha'] = $orden->orden_fecha;

            $productos = $orden->productos;

            $arr_productos = [];
            foreach ($productos as $producto) {
                # code...
                $arr_productos [$producto->producto_id] = $producto->producto_nombre;
            }

            $resp['orden_productos'] = $arr_productos;

            return response()->json($resp);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    }
}
