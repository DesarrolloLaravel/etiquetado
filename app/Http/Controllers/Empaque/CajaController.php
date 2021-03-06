<?php

namespace App\Http\Controllers\Empaque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests\OrdenDespacho\ShowCajaRequest;

use Maatwebsite\Excel\Facades\Excel;

use App\Models\Caja;
use App\Models\Frigorifico;
use App\Models\Camara;
use App\Models\Posicion;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Etiqueta;
use App\Models\Etiqueta_MP;
use Log;

class CajaController extends Controller
{
    public function exportTodayPacking($lote_id, $today = null)
    {
        $lote = Lote::withTrashed()->findOrFail($lote_id);
        if($today == true){
            $date = \Carbon\Carbon::now();
        }
        else $date = null;

        Excel::create('Packing Actual Lote N° '.$lote->lote_id, function($excel) use ($lote, $date) {

            /*$ordenes = $lote->orden_trabajo()
            ->with(['cajas' => function($query) use ($date){
                if(!is_null($date)){
                    $query->whereDate('caja.created_at', '=', $date->format('Y-m-d'))
                    ->has('caja_posicion')
                    ->with('orden_producto.producto','etiqueta','orden_producto.orden');
                }
                else
                    $query->has('caja_posicion')->with('orden_producto.producto','etiqueta','orden_producto.orden');

            }])
            ->get();

            $all_cajas = new Collection();

            foreach ($ordenes as $orden) {
                $cajas = $orden->cajas;
                $all_cajas = $all_cajas->merge($cajas);
            }

            $all_cajas = $all_cajas->groupBy(function ($item, $key) {
                return $item->etiqueta->etiqueta_fecha;
            });*/

            if(!is_null($date)){
                $all_cajas=Caja::join('etiqueta','etiqueta.etiqueta_caja_id','=','caja.caja_id')->whereDate('etiqueta.created_at', '=', $date->format('Y-m-d'))->where('etiqueta.etiqueta_lote_id',$lote->lote_id)->with('etiqueta.lote')->has('etiqueta.caja.caja_posicion')->get();
            }else{
                $all_cajas=Caja::join('etiqueta','etiqueta.etiqueta_caja_id','=','caja.caja_id')->where('etiqueta.etiqueta_lote_id',$lote->lote_id)->with('etiqueta.lote')->has('etiqueta.caja.caja_posicion')->get();
            }

            $all_cajas = $all_cajas->groupBy(function ($item, $key) {
                return $item->etiqueta->etiqueta_fecha;
            });

            $excel->sheet('Resumen', function($sheet) use ($all_cajas, $lote){

                $output = [];
                $sum_neto = 0;
                $sum_bruto = 0;
                $n_cajas = 0;

                foreach ($all_cajas as $group){
                    $sum_neto = $sum_neto + $group->sum('caja_peso_real');
                    $sum_bruto = $sum_bruto + $group->sum('caja_peso_bruto');
                    $n_cajas = $n_cajas + $group->count();

                    $aux['Fecha Producción'] = \Carbon\Carbon::createFromFormat('Y-m-d', $group->first()->etiqueta->etiqueta_fecha)->format('d-m-Y');
                    $aux['Cajas'] = $group->count();
                    $aux['Kilos Neto'] = $group->sum('caja_peso_real');
                    $aux['Kilos Bruto'] = $group->sum('caja_peso_bruto');
                    $output[] = $aux;
                }

                $sheet->rows(array(
                    array('Resumen Total'),
                    array('N° Cajas', $n_cajas),
                    array('Total Neto', $sum_neto),
                    array('Total Bruto', $sum_bruto),
                    ));

                $sheet->fromArray($output, null, 'A6');

            });

            $excel->sheet('Packing Actual', function($sheet) use ($all_cajas, $lote) {

                $output = [];

                foreach ($all_cajas as $group)
                {
                    foreach ($group as $caja) {
                        $producto = $caja->orden_producto->producto;
                        $aux['Año'] = $lote->lote_year;
                        $aux['Lote'] = $lote->lote_id;
                        $aux['Procesadora'] = $lote->procesador->procesador_name;
                        $aux['Productor'] = $lote->productor->productor_name;
                        $aux['Elaborador'] = $lote->elaborador->elaborador_name;
                        $aux['N° Caja'] = $caja->caja_id;
                        $aux['Producto'] = $producto->producto_nombre;
                        $aux['Descripcion'] = $producto->fullName;
                        $aux['Especie'] = $producto->especie->especie_comercial_name;
                        $aux['Calibre'] = $producto->calibre->calibre_nombre;
                        $aux['Calidad'] = $producto->calidad->calidad_nombre;
                        $aux['Cliente'] = $caja->orden_producto->ordenProduccion->cliente->cliente_nombre;
                        $aux['Guia Ingreso'] = $lote->lote_n_documento;
                        $aux['N° Orden'] = $caja->orden_producto->ordenProduccion->orden_id;
                        $aux['Fecha Producción'] = \Carbon\Carbon::createFromFormat('Y-m-d', $caja->etiqueta->etiqueta_fecha)->format('d-m-Y');
                        $aux['Fecha Vencimiento'] = \Carbon\Carbon::createFromFormat('Y-m-d', $lote->lote_fecha_expiracion)->format('d-m-Y');
                        $aux['Codigo'] = $caja->etiqueta->etiqueta_barcode;
                        $aux['Unidades'] = round($caja->caja_unidades);
                        $aux['Kilos Neto'] = round(number_format($caja->caja_peso_real, 2, '.', null), 2);
                        $aux['Kilos Bruto'] = round(number_format($caja->caja_peso_bruto, 2, '.', null), 2);
                        $output[] = $aux;
                    }
                }

                $sheet->setAutoFilter('A1:Z999999');
                $sheet->fromArray($output);

            });
        })->export('xls');
}

public function max()
{
    $proxima_caja = Caja::withTrashed()->max('caja_id') + 1;
    $resp = [];

    $resp['proxima_caja'] = $proxima_caja;

    return $resp;
}

public function informeFecha(Request $request)
{

    Log::info($request->fecha);



    Excel::create('Informe de Produccion del '.$request->fecha, function($excel) use ($request) {
       $fecha_i = \Carbon\Carbon::createFromFormat('d-m-Y', $request->fecha)->format('Y-m-d 00:00:00');
       $fecha_f = \Carbon\Carbon::createFromFormat('d-m-Y', $request->fecha)->format('Y-m-d 23:59:59');
    //materia prima recepcionada en frigorifico
       $materia_prima_recep = Etiqueta_MP::where('etiqueta_mp_estado','RECEPCIONADO')->whereBetween('updated_at',array($fecha_i,$fecha_f))->with('lote','lote.especie','producto')->orderBy('etiqueta_mp_lote_id')->get();
       

       $excel->sheet('Materia Prima Recepcionada', function($sheet) use ( $materia_prima_recep){

        if ($materia_prima_recep->count()>0) {

        
            foreach ($materia_prima_recep as $mpp) {
                $aux['#']=$mpp->etiqueta_mp_id;
                $aux['Codigo Barra']=$mpp->etiqueta_mp_barcode;
                $aux['Lote']=$mpp->etiqueta_mp_lote_id;
                $aux['Especie']=$mpp->lote->especie->especie_comercial_name;
                $aux['Producto'] = $mpp->producto->getFullName();
                $aux['Kilos'] = $mpp->etiqueta_mp_peso;
                $aux['Cajas'] = $mpp->etiqueta_mp_cantidad_cajas;
                $aux['Vencimiento'] = $mpp->lote->lote_fecha_expiracion;
                $output[] = $aux;
            }
        }else{
            $output[]="";
        }
            $sheet->setAutoFilter('A1:Z999999');    
            $sheet->fromArray($output);
       
        });

       $materia_prima_prod = Etiqueta_MP::where('etiqueta_mp_estado','PRODUCCIÓN')->whereBetween('updated_at',array($fecha_i,$fecha_f))->with('lote','lote.especie','producto')->orderBy('etiqueta_mp_lote_id')->get();

      
       $excel->sheet('Materia Prima a Produccion', function($sheet) use ( $materia_prima_prod){
        if ($materia_prima_prod->count()>0) {
            foreach ($materia_prima_prod as $mpp) {
                $aux['#']=$mpp->etiqueta_mp_id;
                $aux['Codigo Barra']=$mpp->etiqueta_mp_barcode;
                $aux['Lote']=$mpp->etiqueta_mp_lote_id;
                $aux['Especie']=$mpp->lote->especie->especie_comercial_name;
                $aux['Producto'] = $mpp->producto->getFullName();
                $aux['Kilos'] = $mpp->etiqueta_mp_peso;
                $aux['Cajas'] = $mpp->etiqueta_mp_cantidad_cajas;
                $aux['Vencimiento'] = $mpp->lote->lote_fecha_expiracion;
                $output[] = $aux;
            }
        }else{
            $output[]="";
        }
            $sheet->setAutoFilter('A1:Z999999');    
            $sheet->fromArray($output);
       
        });

        $etiqueta_pt = Etiqueta::where('etiqueta_estado','NO RECEPCIONADA')->whereBetween('created_at',array($fecha_i,$fecha_f))->with('lote')->get();


         $excel->sheet('Etiquetas No Recepcionado', function($sheet) use ( $etiqueta_pt){

        if ($etiqueta_pt->count()>0) {
            foreach ($etiqueta_pt as $mpp) {
                $aux['#']=$mpp->etiqueta_caja_id;
                $aux['Codigo Barra']=$mpp->etiqueta_barcode;
                $aux['Lote']=$mpp->etiqueta_lote_id;
                $aux['Especie']=$mpp->lote->especie->especie_comercial_name;
                $aux['Producto'] = $mpp->caja->orden_producto->producto->getFullName();
                $aux['Kilos Brutos'] = $mpp->caja->caja_peso_bruto;
                $aux['Glaseado'] = $mpp->caja->caja_glaseado;
                $aux['Kilos Reales'] = $mpp->caja->caja_peso_real;
                $aux['Unidades'] = $mpp->caja->caja_unidades;
                $aux['Vencimiento'] = $mpp->lote->lote_fecha_expiracion;

                $output[] = $aux;
            }
        }else{
            $output[]="";
        }
            $sheet->setAutoFilter('A1:Z999999');    
            $sheet->fromArray($output);
       
        });


        $etiqueta_rp = Etiqueta::where('etiqueta_estado','RECEPCIONADA')->whereBetween('created_at',array($fecha_i,$fecha_f))->with('lote')->get();
            
            $excel->sheet('Producto Terminado Recepcionado', function($sheet) use ( $etiqueta_rp){

            if ($etiqueta_rp->count()>0) {
                    
                foreach ($etiqueta_rp as $mpp) {
                    $aux['#']=$mpp->etiqueta_caja_id;
                    $aux['Codigo Barra']=$mpp->etiqueta_barcode;
                    $aux['Lote']=$mpp->etiqueta_lote_id;
                    $aux['Especie']=$mpp->lote->especie->especie_comercial_name;
                    $aux['Producto'] = $mpp->caja->orden_producto->producto->getFullName();
                    $aux['Kilos Brutos'] = $mpp->caja->caja_peso_bruto;
                    $aux['Glaseado'] = $mpp->caja->caja_glaseado;
                    $aux['Kilos Reales'] = $mpp->caja->caja_peso_real;
                    $aux['Unidades'] = $mpp->caja->caja_unidades;
                    $aux['Vencimiento'] = $mpp->lote->lote_fecha_expiracion;

                    $output[] = $aux;
                }
            }else{
                $output[]="";
            }
            $sheet->setAutoFilter('A1:Z999999');    
            $sheet->fromArray($output);
       
        });


   })->export('xls');

}

   public function exportHistoryPacking($lote_id)
   {
    $lote = Lote::withTrashed()->findOrFail($lote_id);

    Excel::create('Packing Historico Lote N° '.$lote->lote_id, function($excel) use ($lote) {

        $ordenes = $lote->orden_produccion()
        ->has('historyCajas.caja_posicion')
        ->with(['historyCajas' => function($query){
            $query->has('etiqueta')->has('caja_posicion')->with('etiqueta');
        },
        'historyCajas.despachoCaja.despachoLote.orden',
        'historyCajas.orden_producto.orden.cliente',
        'historyCajas.orden_producto.producto'])
        ->get();

        $all_cajas = new Collection();

        foreach ($ordenes as $orden) {
            $cajas = $orden->historyCajas;
            $all_cajas = $all_cajas->merge($cajas);
        }

        $all_cajas = $all_cajas->groupBy(function ($item, $key) {
            return $item->etiqueta->etiqueta_fecha;
        });

        $excel->sheet('Resumen', function($sheet) use ($all_cajas, $lote){

            $output = [];
            $sum_neto = 0;
            $sum_bruto = 0;
            $n_cajas = 0;

            foreach ($all_cajas as $group){
                $sum_neto = $sum_neto + $group->sum('caja_peso_real');
                $sum_bruto = $sum_bruto + $group->sum('caja_peso_bruto');
                $n_cajas = $n_cajas + $group->count();

                $aux['Fecha Producción'] = \Carbon\Carbon::createFromFormat('Y-m-d', $group->first()->etiqueta->etiqueta_fecha)->format('d-m-Y');
                $aux['Cajas'] = $group->count();
                $aux['Kilos Neto'] = $group->sum('caja_peso_real');
                $aux['Kilos Bruto'] = $group->sum('caja_peso_bruto');
                $output[] = $aux;
            }

            $sheet->rows(array(
                array('Resumen Total'),
                array('N° Cajas', $n_cajas),
                array('Total Neto', $sum_neto),
                array('Total Bruto', $sum_bruto),
                ));

            $sheet->fromArray($output, null, 'A6');

        });

        $excel->sheet('Packing Historico', function($sheet) use ($all_cajas, $lote) {

            $output = [];

            $sum_neto = 0;
            $sum_bruto = 0;

            foreach ($all_cajas as $group)
            {
                foreach ($group as $caja) {
                    $producto = $caja->orden_producto->producto;
                    $aux['Año'] = $lote->lote_year;
                    $aux['Lote'] = $lote->lote_id;
                    $aux['Procesadora'] = $lote->procesador->procesador_name;
                    $aux['Productor'] = $lote->productor->productor_name;
                    $aux['Elaborador'] = $lote->elaborador->elaborador_name;
                    $aux['N° Caja'] = $caja->caja_id;
                    $aux['Producto'] = $producto->producto_nombre;
                    $aux['Descripcion'] = $producto->fullName;
                    $aux['Especie'] = $producto->especie->especie_comercial_name;
                    $aux['Calibre'] = $producto->calibre->calibre_nombre;
                    $aux['Calidad'] = $producto->calidad->calidad_nombre;
                    $aux['Cliente'] = $caja->orden_producto->orden->cliente->cliente_nombre;
                    $aux['Guia Ingreso'] = $lote->lote_n_documento;
                    $aux['N° Orden'] = $caja->orden_producto->orden->orden_id;
                    $aux['Fecha Producción'] = \Carbon\Carbon::createFromFormat('Y-m-d', $caja->etiqueta->etiqueta_fecha)->format('d-m-Y');
                    $aux['Fecha Vencimiento'] = \Carbon\Carbon::createFromFormat('Y-m-d', $lote->lote_fecha_expiracion)->format('d-m-Y');
                    $aux['Codigo'] = $caja->etiqueta->etiqueta_barcode;
                    $aux['Unidades'] = round($caja->caja_unidades);
                    $aux['Kilos Neto'] = round(number_format($caja->caja_peso_real, 2, '.', null), 2);
                    $aux['Kilos Bruto'] = round(number_format($caja->caja_peso_bruto, 2, '.', null), 2);

                    if (!is_null($caja->deleted_at) && $caja->etiqueta->etiqueta_estado != 'ANULADA'){
                        $despacho = $caja->despachoCaja->despachoLote->orden;
                        $aux['Despachado'] = 'SI';
                        $aux['Fecha'] = \Carbon\Carbon::createFromFormat('Y-m-d', $despacho->orden_fecha)->format('d-m-Y');
                        $aux['Guia Despacho'] = $despacho->orden_guia;
                    }
                    else{
                        $aux['Despachado'] = 'NO';
                        $aux['Fecha'] = '';
                        $aux['Guia Despacho'] = '';
                    }
                    $output[] = $aux;

                    $sum_neto = $sum_neto + $caja->caja_peso_real;
                    $sum_bruto = $sum_bruto + $caja->caja_peso_bruto;
                }
            }

            $sheet->setAutoFilter('A1:Z999999');
            $sheet->fromArray($output);

        });
    })->export('xls');
}

public function getCajasByLoteOfProduct(Request $request)
{
        //funcion que retorna el listado de cajas de un lote de determinado producto
    if($request->ajax())
    {
        $lote = Lote::findOrFail($request->lote_id);
        $cajas = $lote->getCajas();

        $producto = Producto::findOrFail($request->producto_id);
        $cajas_producto = $producto->cajas;

        $cajas = $cajas->intersect($cajas_producto);
        $n_cajas = $cajas->count();
        $kilos = $cajas->sum('caja_peso_real');

        return response()->json(['estado' => 'ok', 'cajas' => $cajas,
            'n_cajas' => $n_cajas, 'kilos' => $kilos]);
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
            $resp = [];
            if($request->q == "search" && 
                (isset($request->select_frigorifico) || isset($request->select_camara) || isset($request->select_lote) ))
            {
                if(isset($request->select_camara) && $request->select_camara != "") //camara seleccionada
                {
                    $posiciones = Camara::findOrFail($request->select_camara)
                    ->posiciones()
                    ->with(['caja_posicion.etiqueta',
                        'caja_posicion.etiqueta.lote' => function($query){
                            $query->withTrashed();
                        }])
                    ->get();
                }
                else if(isset($request->select_frigorifico) && $request->select_frigorifico != "") // frigorifico seleccionado

                {
                    $posiciones = Frigorifico::findOrFail($request->select_frigorifico)
                    ->posiciones()
                    ->with(['caja_posicion.etiqueta',
                        'caja_posicion.etiqueta.lote' => function($query){
                            $query->withTrashed();
                        }])
                    ->get();
                }
                else if(isset($request->select_frigorifico) && $request->select_frigorifico == "") // Solo lote seleccionado

                {
                    $posiciones = Posicion::with(['caja_posicion.etiqueta',
                        'caja_posicion.etiqueta.lote' => function($query){
                            $query->withTrashed();
                        }])->get();
                }

                $cajas = new Collection;
                foreach ($posiciones as $posicion) {
                    # code...
                    $cajas = $cajas->merge($posicion->caja_posicion);
                }

                //dd($cajas->first()->orden_producto->orden->historyLotes()->get());

                if(isset($request->select_lote) && $request->select_lote != "")
                {
                    $lote = Lote::findOrFail($request->select_lote);
                    $cajas_lote = $lote->getCajas();
                    $cajas = $cajas->intersect($cajas_lote);
                    // $cajas = $cajas->where('etiqueta.lote.lote_id', $request->select_lote);




                    if($cajas->count() == 0)
                    {
                        return '{ 
                            "n_cajas" : 0,
                            "total_peso_real" : 0,
                            "total_peso_bruto" : 0,
                            "data" : []}';
                        }


            //inicializo el json
                        $dt_json = '{ 
                            "n_cajas" : '.$cajas->count().',
                            "total_peso_real" : '.$cajas->sum('caja_peso_real').',
                            "total_peso_bruto" : '.$cajas->sum('caja_peso_bruto').',
                            "data" : [';

            //para cada compañia
                            foreach ($cajas as $caja) {
                //completo el json
                                $dt_json .= '["'.$caja->caja_id.'","'
                                .$caja->etiqueta->lote->lote_id.'","'
                                .$caja->etiqueta->etiqueta_barcode.'","'
                                .$caja->caja_peso_real.'","'
                                .$caja->caja_peso_bruto.'"],';
                            }
            //elimino la ultima coma del json
                            $dt_json = substr($dt_json, 0, -1);
            //se cierra el json
                            $dt_json.= "] }";
            //envio respuesta al cliente
                            return $dt_json;


                        }
                    }

                    if($cajas->count() == 0)
                    {
                        return '{ 
                            "n_cajas" : 0,
                            "total_peso_real" : 0,
                            "total_peso_bruto" : 0,
                            "data" : []}';
                        }
            //inicializo el json
                        $dt_json = '{ 
                            "n_cajas" : '.$cajas->count().',
                            "total_peso_real" : '.$cajas->sum('caja_peso_real').',
                            "total_peso_bruto" : '.$cajas->sum('caja_peso_bruto').',
                            "data" : [';

            //para cada compañia
                            foreach ($cajas as $caja) {
                //completo el json
                                $dt_json .= '["'.$caja->caja_id.'","'
                                .$caja->etiqueta->lote->lote_id.'","'
                                .$caja->etiqueta->etiqueta_barcode.'","'
                                .$caja->caja_peso_real.'","'
                                .$caja->caja_peso_bruto.'"],';
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

                            $lotes = [''=>'Todos'] +
                            Lote::withTrashed()->has('etiqueta')
                            ->get()
                            ->lists('lote_id', 'lote_id')
                            ->all();

                            return view('empaque.stock.index', compact('lotes'));
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
    //en el form request valido que la caja corresponda a la orden de despacho
    public function show(ShowCajaRequest $request)
    {
        //
        if($request->ajax())
        {
            if($request->q == "despacho")
            {
                $etiqueta = Etiqueta::where('etiqueta_barcode', $request->etiqueta_barcode)->first();

                $caja = $etiqueta->caja;

            }

            return response()->json(["estado" => "ok", "caja" => $caja]);
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
