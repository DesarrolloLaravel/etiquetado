<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\OrdenTrabajo\CreateRequest;
use App\Http\Requests\OrdenTrabajo\UpdateRequest;

use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoProducto;
use App\Models\Especie;
use App\Models\Producto;
use App\Models\OrdenProduccion;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        if($request->ajax())
        {
            $ordenes = OrdenTrabajo::with('especie','producto','ordenProduccion')->get();

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
                                .$orden->orden_trabajo_id.'","'
                                .$orden->ordenProduccion->orden_id.'","'
                                .$orden->especie->especie_name.'","'
                                .$orden->producto->fullName.'","'
                                .$orden->orden_trabajo_peso_total.'","'
                                .\Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_trabajo_fecha)->format('d-m-Y').'"],';
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
            return view('admin.orden_trabajo.index');
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
            $ordenProduccion = [''=>'Ninguno'] +
                OrdenProduccion::orderBy('orden_id', 'ASC')
                    ->get()
                    ->lists('orden_id','orden_id')
                    ->all();

            $especies =[''=>'Ninguno'];
                
            $productos =[''=>'Ninguno'];

            $proxima_orden = OrdenTrabajo::all()->max('orden_trabajo_id')+1;

            $orden_fecha = \Carbon\Carbon::now()->format('d-m-Y');
            
            $view = \View::make('admin.orden_trabajo.fields')
                    ->with('ordenProduccion', $ordenProduccion)
                    ->with('proxima_orden', $proxima_orden)
                    ->with('orden_trabajo_fecha', $orden_fecha)
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
    public function show($id)
    {
        //
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
