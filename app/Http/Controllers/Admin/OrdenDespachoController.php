<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests\OrdenDespacho\CreateRequest;
use App\Http\Requests\OrdenDespacho\DiscountRequest;

use App\Models\OrdenDespacho;
use App\Models\OrdenDespachoLote;
use App\Models\OrdenProduccion;
use App\Models\Producto;
use App\Models\Lote;
use App\Models\Etiqueta;
use App\Models\Caja;

class OrdenDespachoController extends Controller
{
    public function exportPacking(Request $request)
    {
        //dd($request->orden);
        $orden = OrdenDespacho::findOrFail($request->orden);

        Excel::create('Orden Despacho '.$orden->orden_id, function($excel) use ($orden) {
 
            $excel->sheet('Packing', function($sheet) use ($orden) {

                $detalle_lote = $orden->despacho_lote;

                $detalles = [];
                $cajas = [];
                foreach ($detalle_lote as $detalle) {

                    $cajas_despacho = $detalle->all_cajas;
                    
                    $aux = [];
                    foreach ($cajas_despacho as $caja) {
                        # code...
                        $lote = $caja->orden_producto->orden->lote;
                        $producto = $caja->orden_producto->producto;
                        $aux['Año'] = $lote->lote_year;
                        $aux['Lote'] = $lote->lote_id;
                        $aux['Procesadora'] = $lote->procesador->procesador_name;
                        $aux['Productor'] = $lote->productor->productor_name;
                        $aux['Elaborador'] = $lote->elaborador->elaborador_id;
                        $aux['N° Caja'] = $caja->caja_id;
                        $aux['Producto'] = $producto->producto_nombre;
                        $aux['Descripcion'] = $producto->producto_descripcion;
                        $aux['Calidad'] = \Config::get('options.calidad')[$lote->lote_calidad_id];
                        $aux['Cliente'] = $orden->cliente->cliente_nombre;
                        $aux['Guia'] = $orden->orden_guia;
                        $aux['N° Orden'] = $orden->orden_id;
                        $aux['Codigo'] = $caja->etiqueta->etiqueta_barcode;
                        $aux['Kilos'] = $caja->caja_peso_real;
                        $cajas[] = $aux;
                    }
                }
 
                $sheet->fromArray($cajas);
 
            });
        })->export('xls');
    }
    
    public function resume(Request $request)
    {
        if($request->ajax())
        {
            $orden = OrdenDespacho::findOrFail($request->orden_id);

            $detalle_orden = $orden->despacho_lote;

            $total_cajas = 0;
            $total_kilos = 0;
            foreach ($detalle_orden as $detalle) {
                # code...
                $total_cajas = $total_cajas + $detalle->cajas()->count();
                $total_kilos = $total_kilos + $detalle->cajas()->sum('caja_peso_real');
            }

            $resp['orden_id'] = $orden->orden_id;
            $resp['orden_estado'] = $orden->orden_estado;
            $resp['orden_cliente'] = $orden->cliente->cliente_nombre;
            $resp['orden_guia'] = $orden->orden_guia;
            $resp['orden_fecha'] = $orden->orden_fecha;

            $resp['total_cajas'] = $total_cajas;
            $resp['total_kilos'] = $total_kilos;
            $resp['total_cajas_plan'] = $detalle_lote->sum('despacho_cajas_plan');
            $resp['total_kilos_plan'] = $detalle_lote->sum('despacho_kilos_plan');
        }
    }

    public function discount(DiscountRequest $request)
    {

        \DB::beginTransaction();

        try{
            $orden = OrdenDespacho::findOrFail($request->orden_id);

            $orden->orden_estado = "DESPACHADO";

            $orden->save();

            $detalle_orden = $orden->despacho_lote;

            foreach ($detalle_orden as $detalle) {
                
                $cajas = $detalle->cajas;

                if($cajas->count() > 0)
                {
                    foreach ($cajas as $caja) {
                        $caja->input_output()->attach($caja->caja_posicion->first()->posicion_id,
                        ['io_tipo' => 'SALIDA', 'io_proceso' => 'DESPACHO']);
                        $caja->posicion_caja->first()->delete();
                        $caja->delete();
                    }
                }
            }
            $resp['estado'] = "ok";
        }
        catch ( Exception $e ){
            $resp['estado'] = "nok";
            \DB::rollback();
        }

        \DB::commit();

        return response()->json($resp);
    }

    public function execute(Request $request)
    {
        return view('admin.orden_despacho.execute');
    }

    public function next(Request $request)
    {
        if($request->ajax())
        {
            $proximo_despacho = OrdenDespacho::withTrashed()->max('orden_id') + 1;

            return response()->json($proximo_despacho);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $ordenes = OrdenDespacho::all();

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
            else if($request->q == "pre-embarque")
            {
                $ordenes = OrdenDespacho::has('despacho_lote')
                        ->where('orden_estado', 'PRE-EMBARQUE')
                        ->get();
                        
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
                $dt_json .= '["'.$orden->orden_id.'","'
                                .$orden->orden_estado.'","'
                                .$orden->cliente->cliente_nombre.'","'
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
            return view('admin.orden_despacho.index');
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
        /*$lote = Producto::has('orden_produccion.lote')
                        ->with('orden_produccion.lote')
                        ->get()
                        ->where('orden_produccion.lote.lote_id', 1);

        dd($lote);*/
        $proximo_despacho = OrdenDespacho::max('orden_id')+1;
        $clientes = [''=>'Ninguno'] +
            Cliente::orderBy('cliente_nombre', 'ASC')
                ->get()
                ->lists('cliente_nombre','cliente_id')
                ->all();

        return view('admin.orden_despacho.create', compact('proximo_despacho', 'clientes'));
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
        if($request->ajax())
        {
            $orden_fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->orden_fecha);
            //dd($request->all(), \Config::get('options.estado_despacho')[$request->orden_tipo]);
            $info = array(
                'orden_cliente_id'      => $request->cliente_id,
                'orden_estado'          => \Config::get('options.estado_despacho')[$request->orden_tipo],
                'orden_guia'            => $request->orden_guia,
                'orden_fecha'           => $orden_fecha
            );

            \DB::beginTransaction();

            try{
                $orden = OrdenDespacho::create($info);

                if($request->detail != "")
                {
                    $detail = explode(",", $request->detail);
                    if((count($detail) % 5) == 0)
                    {
                        $n_elem = count($detail)/5;
                        $arr = [];
                        for ($i=0; $i < $n_elem; $i++)
                        {
                            $arr [] = ['despacho_orden_id' => $orden->orden_id,
                                'despacho_lote_id' => $detail[5*$i],
                                'despacho_producto_id' => $detail[(5*$i)+1],
                                'despacho_cajas_plan' => $detail[(5*$i)+3],
                                'despacho_kilos_plan' => $detail[(5*$i)+4]
                            ];
                            $despacho_lote = new OrdenDespachoLote();
                            $despacho_lote->despacho_lote_id = $detail[5*$i];
                            $despacho_lote->despacho_producto_id = $detail[(5*$i)+1];
                            $despacho_lote->despacho_cajas_plan = $detail[(5*$i)+3];
                            $despacho_lote->despacho_kilos_plan = $detail[(5*$i)+4];
                            $despacho_lote->orden()->associate($orden);
                            $despacho_lote->save();

                        }
                    }
                }
            }
            catch ( Exception $e ){
                \DB::rollback();
                return response()->json([
                    "estado" => "nok"
                ]);
            }
            \DB::commit();

            //envio respuesta al cliente
            return response()->json([
                "estado" => "ok"
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
        //
        $orden = OrdenDespacho::with('despacho_lote.lote',
                        'despacho_lote.producto',
                        'despacho_lote.cajas.etiqueta',
                        'despacho_lote.all_cajas.etiqueta')
                        ->findOrFail($request->orden_id);

        if($request->ajax())
        {
            $resp = [];

            $fecha = \Carbon\Carbon::createFromFormat('Y-m-d', $orden->orden_fecha)->format('d-m-Y'); 

            $resp['orden_id'] = $orden->orden_id;
            $resp['orden_estado'] = $orden->orden_estado;
            $resp['orden_cliente'] = $orden->cliente->cliente_nombre;
            $resp['orden_guia'] = $orden->orden_guia;
            $resp['orden_fecha'] = $fecha;

            if($request->q == "complete")
                $detalle_lote = $orden->despacho_lote()->with('lote','producto','all_cajas.etiqueta')->get();
            else
                $detalle_lote = $orden->despacho_lote()->with('lote','producto','cajas.etiqueta')->get();


            //dd($detalle_lote);

            $detalles = [];
            $cajas = [];
            foreach ($detalle_lote as $detalle) {
                # code...
                $arr_detalle['lote_id'] = $detalle->lote->lote_id;
                $arr_detalle['producto'] = $detalle->producto->producto_nombre;
                $arr_detalle['cajas_plan'] = $detalle->despacho_cajas_plan;
                $arr_detalle['kilos_plan'] = $detalle->despacho_kilos_plan;

                $detalles [] = $arr_detalle;
                $aux = [];

                if($request->q == "complete")
                {
                    foreach ($detalle->all_cajas as $caja) {
                        # code...
                        $aux['id'] = $caja->caja_id;
                        $aux['codigo'] = $caja->etiqueta->etiqueta_barcode;
                        $aux['kilos'] = $caja->caja_peso_real;
                        $cajas[] = $aux;
                    }
                }
                else
                {
                    foreach ($detalle->cajas as $caja) {
                        # code...
                        $aux['id'] = $caja->caja_id;
                        $aux['codigo'] = $caja->etiqueta->etiqueta_barcode;
                        $aux['kilos'] = $caja->caja_peso_real;
                        $cajas[] = $aux;
                    }
                }
                

            }
            $resp['orden_cajas'] = $cajas;
            
            $resp['orden_detalle'] = $detalles;
            $resp['orden_total_cajas'] = $detalle_lote->sum('despacho_cajas_plan');
            $resp['orden_total_kilos'] = $detalle_lote->sum('despacho_kilos_plan');

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
    public function update(Request $request)
    {
        //
        if($request->ajax())
        {
            \DB::beginTransaction();

            try{
                $orden = OrdenDespacho::with('despacho_lote.cajas')->findOrFail($request->orden_id);
                //detalles de la orden
                $orden_detalle = $orden->despacho_lote;
                //para cada detalle
                foreach ($orden_detalle as $detalle) {
                    //para cada detalle, borro todas las cajas asociadas
                    $detalle->cajas()->detach();
                }

                $cajas_despacho = $request->arr_detail;

                for ($i=0; $i < count($cajas_despacho); $i++) { 
                    # code...
                    $caja = Caja::findOrFail($cajas_despacho[$i][0]);
                    $producto = $caja->orden_producto->producto;
                    $lote = $caja->orden_producto->orden->lote;

                    $despacho_lote = $orden->despacho_lote()
                                    ->where('despacho_lote_id', $lote->lote_id)
                                    ->where('despacho_producto_id', $producto->producto_id)
                                    ->first();

                    $despacho_lote->cajas()->attach($caja->caja_id);
                }

                $resp["estado"] = "ok";
            }
            catch ( Exception $e ){
                $resp["estado"] = "nok";
                \DB::rollback();
            }
            \DB::commit();
            
            //envio respuesta al cliente
            return response()->json($resp);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            //se crea el validador con la información enviada desde el cliente
            //y con las reglas de validación respectiva
            $v = \Validator::make($request->all(), [
                'orden_id' => 'required|exists:orden_despacho,orden_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la compañia a eliminar
            $orden = OrdenDespacho::findOrFail($request->orden_id);
            if($orden->orden_estado == "DESPACHADO"){
                return response()->json(["La orden seleccionda ya fue despachada"
                ]);
            }
            //elimino la compañia
            $orden->delete();

            //respuesta al cliente
            return response()->json(["ok"
            ]);
        }
    }
}
