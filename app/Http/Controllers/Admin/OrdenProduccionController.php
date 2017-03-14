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
        if($request->ajax())
        {
            if($request->q == "etiqueta")
            {
                $ordenes = Lote::find($request->lote_id)->orden_produccion()->with('lote')->get();
            }
            else
            {
                $ordenes = OrdenProduccion::has('lote')->with('lote')->get();
            }
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
                                .$orden->lote->lote_id.'","'
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
            $lotes = [''=>'Ninguno'] + 
                        Lote::where('lote_produccion', 'SI')
                            ->get()
                            ->lists('lote_id', 'lote_id')
                            ->all();

            $productos = [''=>'Ninguno'] + 
                        Producto::orderBy('producto_nombre', 'ASC')
                        ->get()
                        ->lists('fullName','producto_id')
                        ->all();

            $clientes = [''=>'Ninguno'] +
                Cliente::orderBy('cliente_nombre', 'ASC')
                    ->get()
                    ->lists('cliente_nombre','cliente_id')
                    ->all();

            $proxima_orden = OrdenProduccion::all()->max('orden_id')+1;

            $orden_fecha = \Carbon\Carbon::now()->format('d-m-Y');
            $orden_fecha_inicio = \Carbon\Carbon::now()->format('d-m-Y');
            $orden_fecha_compromiso = \Carbon\Carbon::now()->format('d-m-Y');

            $view = \View::make('admin.orden_produccion.fields')
                    ->with('productos', $productos)
                    ->with('clientes', $clientes)
                    ->with('lotes', $lotes)
                    ->with('proxima_orden', $proxima_orden)
                    ->with('orden_fecha', $orden_fecha)
                    ->with('orden_fecha_inicio', $orden_fecha_inicio)
                    ->with('orden_fecha_compromiso', $orden_fecha_compromiso);

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
        $orden = OrdenProduccion::with('productos','lote')->findOrFail($request->orden_id);

        if($request->ajax())
        {
            $resp = [];

            $resp['orden_id'] = $orden->orden_id;
            $resp['orden_lote_id'] = $orden->lote->lote_id;
            $resp['orden_descripcion'] = $orden->orden_descripcion;
            $resp['orden_fecha'] = $orden->orden_fecha;

            $productos = $orden->productos->sortBy('producto_nombre')->lists('producto_id','fullName');

            $resp['orden_productos'] = $productos;

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
            $lotes = [''=>'Ninguno'] + 
                        Lote::where('lote_produccion', 'SI')
                            ->get()
                            ->lists('lote_id', 'lote_id')
                            ->all();

            $productos = [''=>'Ninguno'] + 
                        Producto::orderBy('producto_nombre', 'ASC')
                        ->get()
                        ->lists('fullName','producto_id')
                        ->all();

            $clientes = [''=>'Ninguno'] +
                Cliente::orderBy('cliente_nombre', 'ASC')
                    ->get()
                    ->lists('cliente_nombre','cliente_id')
                    ->all();

            $orden->productos;

            $orden_fecha = \Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_fecha)->format('d-m-Y');
            $orden_fecha_inicio = \Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_fecha_inicio)->format('d-m-Y');
            $orden_fecha_compromiso = \Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_fecha_compromiso)->format('d-m-Y');

            $view = \View::make('admin.orden_produccion.fields')
                    ->with('productos', $productos)
                    ->with('clientes', $clientes)
                    ->with('lotes', $lotes)
                    ->with('proxima_orden', $orden->orden_id)
                    ->with('orden_fecha', $orden_fecha)
                    ->with('orden_fecha_inicio', $orden_fecha_inicio)
                    ->with('orden_fecha_compromiso', $orden_fecha_compromiso);

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
