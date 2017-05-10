<?php

namespace App\Http\Controllers\Geren;

use Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Database\Eloquent\Collection;

use App\Models\OrdenDespacho;
use App\Models\OrdenTrabajo;
use App\Models\OrdenDespachoLote;
use App\Models\OrdenDespachoCaja;
use App\Models\OrdenProduccion;
use App\Models\Producto;
use App\Models\Lote;
use App\Models\Etiqueta;
use App\Models\Etiqueta_MP;
use App\Models\Caja;
use App\Models\InputOutput;
use App\Models\CajaPosicion;

class OrdenDespachoController extends Controller
{
    public function cargar_etiqueta(Request $request){

        Log::info($request->etiqueta);

        $et = Etiqueta::where('etiqueta_barcode',$request->etiqueta)->where('etiqueta_estado',"RECEPCIONADA")->count();

        if($et == 1){

            $eti = Etiqueta::where('etiqueta_barcode',$request->etiqueta)->where('etiqueta_estado',"RECEPCIONADA")->first();

            Log::info($eti->etiqueta_id);

            $caj = Caja::where('caja_id',$eti->etiqueta_caja_id)->first();

            $ot = OrdenTrabajo::where('orden_trabajo_id',$caj->caja_ot_producto_id)->first();

            $prod = Producto::where('producto_id',$ot->orden_trabajo_producto)->first();

            return response()->json(["estado" => "ok","caja" => $caj,"producto" => $prod->fullName]);
        }
        else{

            return response()->json(["estado" => "nok"]);   

        }
    }

    public function despachados(){

        return view('geren.despacho.despachados');
    }

   
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $ordenes = OrdenDespacho::where('orden_estado','<>','DESPACHADO')->get();

            if($request->q == "all")
            {
                if($ordenes->count() == 0)
                {
                    return '{"data":[]}';
                }
            }
            else if($request->q == "despacho")
            {
                $ordenes = OrdenDespacho::has('despacho_lote')
                        ->where('orden_estado', 'DESPACHO')
                        ->get();
                        
                if($ordenes->count() == 0)
                {
                    return '{"data":[]}';
                }
            }
            else if($request->q == "despachado")
            {
                $ordenes = OrdenDespacho::where('orden_estado', 'DESPACHADO')
                ->get();
                        
                if($ordenes->count() == 0)
                {
                    return '{"data":[]}';
                }
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
                $dt_json .= '["'.$orden->orden_id.'","'
                                .$orden->orden_estado.'","'
                                .$orden->orden_orden_produccion.'","'
                                .$orden->orden_guia.'","'
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
            return view('geren.despacho.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if($request->ajax())
        {
            
            Log::info("Create");          
            $estado = ['1' => 'Pre-Despacho','2' => 'Despacho', '3' => 'Despachado'];

            Log::info($estado);

            $despacho = OrdenDespacho::all()->max('orden_id')+1;

            log::info($despacho);

            $despacho_fecha = \Carbon\Carbon::now()->format('d-m-Y');

            Log::info($despacho_fecha);
            
            $view = \View::make('geren.despacho.fields')
                    ->with('despacho', $despacho)
                    ->with('despacho_fecha', $despacho_fecha)
                    ->with('estado',$estado);

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
        if($request->ajax()){

            Log::info($request->cajas);

            $estado = $request->despacho_estado;

            Log::info($estado);

            $orden_fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->despacho_fecha);
            $info = array(
                'orden_estado'    => $estado,
                'orden_orden_produccion'           => $request->orden_id,
                'orden_guia'    => $request->despacho_guia,
                'orden_fecha'=> $orden_fecha
            );
            
            $orden = OrdenDespacho::create($info);

            $caj = explode(',',$request->cajas);
            $etis = explode(',',$request->etiquetas);

            for ($i=0; $i < count($etis) ; $i++) { 

                $et = Etiqueta::where('etiqueta_barcode',$etis[$i])->first();

                $caja = Caja::where('caja_id',$caj[$i])->first();

                $ot = OrdenTrabajo::where('orden_trabajo_id',$caja->caja_ot_producto_id)->first();
           
                $pp = array(

                    'despacho_orden_id' => $orden->orden_id,
                    'despacho_lote_id' => $et->etiqueta_lote_id,
                    'despacho_producto_id' => $ot->orden_trabajo_producto
                );
            
                $opp = OrdenDespachoLote::create($pp); 

                $cc = array(
                    'despacho_caja_caja_id' => $caj[$i], 
                    'despacho_caja_despacho_lote_id' => $opp->despacho_id

                );

                OrdenDespachoCaja::create($cc);

                if($estado == 2 || $estado == 3 ){

                    Log::info("Despacho y Despachado");

                    $io = InputOutput::where('io_caja_id',$caj[$i])->firstOrFail();

                    $inout = array(
                        'io_tipo' => 2,
                        'io_proceso' => 2
                    );

                    $io->fill($inout);
                    $io->save();

                    $pos = CajaPosicion::where('caja_posicion_caja_id',$caj[$i])->firstOrFail();

                    $pos->delete();
                }
            }

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
    public function show(Request $request)
    {
       Log::info("alerta");
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
        Log::info("editar Despacho");

        $estado = ['1' => 'Pre-Despacho','2' => 'Despacho', '3' => 'Despachado'];

        if($request->ajax())
        {

            $orden = OrdenDespacho::with('despacho_lote.lote',
                        'despacho_lote.producto',
                        'despacho_lote.cajas.etiqueta')->findOrFail($request->despacho_id);

            $resp = [];

            $fecha = \Carbon\Carbon::createFromFormat('Y-m-d', $orden->orden_fecha)->format('d-m-Y'); 

            $resp['orden_id'] = $orden->orden_id;
            $resp['orden_estado'] = $orden->orden_estado;
            $resp['orden_orden_produccion'] = $orden->orden_orden_produccion;
            $resp['orden_guia'] = $orden->orden_guia;
            $resp['orden_fecha'] = $fecha;


            if($resp['orden_estado'] == "PRE-DESPACHO"){
                $est = 1;
            }else if($resp['orden_estado'] == "DESPACHO"){
                $est = 2;
            }else{
                $est = 3;
            }

           
           $detalle_lote = $orden->despacho_lote()->with('lote','producto','cajas.etiqueta')->get();

            //dd($detalle_lote);

            $detalles = [];
            $cajas = [];

            foreach ($detalle_lote as $detalle) {
                # code...
                $arr_detalle['lote_id'] = $detalle->lote->lote_id;
                $arr_detalle['producto'] = $detalle->producto->fullName;

                $detalles [] = $arr_detalle;
                $aux = [];
                
                foreach ($detalle->cajas as $caja) {
                    # code...
                    $aux['id'] = $caja->caja_id;
                    $aux['codigo'] = $caja->etiqueta->etiqueta_barcode;
                    $aux['kilos'] = $caja->caja_peso_real;
                    $cajas[] = $aux;
                }
            }

            $resp['orden_cajas'] = $cajas;  
            $resp['orden_detalle'] = $detalles;
          

            $view = \View::make('geren.despacho.fields')
                    ->with('despacho',$resp['orden_id'])
                    ->with('despacho_fecha', $fecha)
                    ->with('estado',$estado);

            $sections = $view->renderSections();

            return response()->json(["estado" => "ok","resp" =>$resp,"estatus" => $est,"section" => $sections['contentPanel']]);
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        Log::info($request->despacho_id);

        if($request->ajax()){

            $caj = explode(',',$request->cajas);
            $etis = explode(',',$request->etiquetas);
            $del = explode(',',$request->del);

            Log::info($caj);
            Log::info($etis);
            Log::info($del);

            $odl = OrdenDespachoLote::where('despacho_orden_id',$request->despacho_id)->firstOrFail();

            Log::info("id");
            Log::info($odl->despacho_id);


            for ($i=0; $i < count($del); $i++) { 
            
                $odc = OrdenDespachoCaja::where('despacho_caja_caja_id',$del[$i])->where('despacho_caja_despacho_lote_id',$odl->despacho_id)->firstOrFail();

                $odc->delete();


                if($request->despacho_estado == 2){

                    $posicion = CajaPosicion::withTrashed()->where('caja_posicion_caja_id',$del[$i])->first();

                    Log::info($posicion);

                    $posicion->restore();

                    $inf = array(
                            'io_tipo' => 'ENTRADA',
                            'io_proceso' => 'PRODUCCION'
                    );

                    $io = InputOutput::where('io_caja_id',$del[$i])->first();

                    $io->fill($inf);

                    $io->save();
                } 

            }
            if(count($caj) > 0){

                for ($i=0; $i < count($etis) ; $i++) { 

                    $cc = array(
                        'despacho_caja_caja_id' => $caj[$i], 
                        'despacho_caja_despacho_lote_id' => $odl->despacho_id

                    );

                    OrdenDespachoCaja::create($cc);

                    if($estado == 2 || $estado == 3 ){

                        Log::info("Despacho y Despachado");

                        $io = InputOutput::where('io_caja_id',$caj[$i])->firstOrFail();

                        $inout = array(
                            'io_tipo' => 2,
                            'io_proceso' => 2
                        );

                        $io->fill($inout);
                        $io->save();

                        $pos = CajaPosicion::where('caja_posicion_caja_id',$caj[$i])->firstOrFail();

                        $pos->delete();
                    }
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        Log::info("alerta");
    }
}
