<?php

namespace App\Http\Controllers\Empaque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\OrdenProduccion;
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
            return view('empaque.orden_produccion.index', compact('ordenes'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
