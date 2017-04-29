<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Log;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\OrdenTrabajo\CreateRequest;
use App\Http\Requests\OrdenTrabajo\UpdateRequest;

use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoProducto;
use App\Models\Especie;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\OrdenProduccion;
use App\Models\OrdenProduccionProducto;
use App\Models\etiqueta_MP;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function cargar_especie(Request $request)
    {
        //
        
        $pro = OrdenProduccionProducto::where('op_producto_orden_id',$request->orden_prod)->with('producto')->get();

        $especies = [];
        $dat = [];

        foreach ($pro as $pp) {
            
            $dat[$pp->producto->especie->especie_id] = $pp->producto->especie->especie_comercial_name;

            Log::info($pp->producto->especie->especie_id);
            Log::info($pp->producto->especie->especie_comercial_name);

            Log::info($dat);
        }

        $especies = $dat;

        Log::info($especies);
        return $especies; 
    }

    public function cargar_producto(Request $request)
    {
        //
        
        $productos = Producto::orderBy('producto_nombre','ASC')
                ->where('producto_especie_id',$request->especie_id)
                ->lists('producto_nombre','producto_id')
                ->all();

        Log::info($productos);

        return $productos; 
    }

 
    public function kilos_eti(Request $request)
    {
        //

        Log::info($request->producto_id);
        Log::info($request->etiqueta_pallet);

        $eti = Etiqueta_MP::where('etiqueta_mp_producto_id',$request->producto_id)->where('etiqueta_mp_estado','NO RECEPCIONAdO')->where('etiqueta_mp_barcode',$request->etiqueta_pallet)
        ->count();

        Log::info($eti);

        if($eti == 1){

            Log::info("Pallet: ".$request->etiqueta_pallet);

            $kilos = Etiqueta_MP::where('etiqueta_mp_barcode',$request->etiqueta_pallet)->first();

            Log::info($kilos);        
        
            return response()->json(["estado" => "ok", "dato" => $kilos]);

        }else{

            return response()->json(["estado" => "nok", "dato" => null]);
        }  
    }

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
    public function store(CreateRequest $request)
    {
        //
        if($request->ajax()){

            $orden_fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->orden_fecha);
            
            $info = array(
                'orden_trabajo_orden_produccion'    => $request->orden_trabajo_orden_produccion,
                'orden_trabajo_especie'           => $request->orden_trabajo_especie,
                'orden_trabajo_producto'    => $request->orden_trabajo_producto,
                'orden_trabajo_fecha'=> $orden_fecha,
                'orden_trabajo_peso_total'      => $request->peso
            );


            $orden = OrdenTrabajo::create($info);

            $prod = explode(',',$request->etiquetas);

            for ($i=0; $i < count($prod) ; $i++) { 
               
                Log::info($prod[$i]);

                $etiq = Etiqueta_MP::where('etiqueta_mp_barcode',$prod[$i])->firstOrFail();

                $pp = array(
                    'ot_producto_orden_trabajo' => $orden->orden_trabajo_id,
                    'ot_producto_etiqueta_pallet' => $etiq->etiqueta_mp_id
                );

                $opp = OrdenTrabajoProducto::create($pp);    

                $info = array('etiqueta_mp_estado' => 'PRODUCCION');
                $etiq->fill($info);
                $etiq->save();   
            }
            
            //envio respuesta al cliente
            return response()->json([
                "ok"
            ]);   
        }
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
    public function edit(Request $request)
    {
        //
        Log::info($request->orden_id);

        $orden = OrdenTrabajo::where('orden_trabajo_id',$request->orden_id)->firstOrFail();

        if($request->ajax())
        {
            $especies = $orden->orden_trabajo_especie;
                
            $productos = $orden->orden_trabajo_producto;

            $orden_producto = $orden->orden_trabajo_orden_produccion;

            $orden_fecha = \Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_trabajo_fecha)->format('d-m-Y');

            $op = OrdenTrabajoProducto::where('ot_producto_orden_trabajo',$request->orden_id)->with('etiqueta')->get();

            Log::info($orden);
            Log::info($op);    
        }

        
        
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
