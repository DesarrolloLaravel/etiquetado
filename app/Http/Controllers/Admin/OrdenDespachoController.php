<?php

namespace App\Http\Controllers\Admin;

use Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Database\Eloquent\Collection;

use App\Models\OrdenDespacho;
use App\Models\OrdenDespachoLote;
use App\Models\OrdenProduccion;
use App\Models\Producto;
use App\Models\Lote;
use App\Models\Etiqueta;
use App\Models\Etiqueta_MP;
use App\Models\Caja;

class OrdenDespachoController extends Controller
{
    public function cargar_pallet(Request $request){

        Log::info($request->orden_id);
        Log::info($request->etiqueta_pallet);

        $eti = Etiqueta_MP::where('etiqueta_mp_barcode',$request->etiqueta_pallet)
        ->count();

        Log::info($eti);

        if($eti == 1){

            Log::info("Pallet: ".$request->etiqueta_pallet);

            $kilos = Etiqueta_MP::with('producto')->where('etiqueta_mp_barcode',$request->etiqueta_pallet)->get();

            Log::info($kilos);        
        
            return response()->json(["estado" => "ok", "dato" => $kilos]);

        }else{

            return response()->json(["estado" => "nok", "dato" => null]);
        }  
    }

   
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
            
            Log::info($ordenes);

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
            return view('admin.despacho.index');
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
            
            $view = \View::make('admin.despacho.fields')
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

            $orden_fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->despacho_fecha);
            $info = array(
                'orden_estado'    => $request->despacho_estado,
                'orden_orden_produccion'           => $request->orden_id,
                'orden_guia'    => $request->despacho_guia,
                'orden_fecha'=> $orden_fecha
            );


            $orden = OrdenDespacho::create($info);

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
        Log::info("alerta");
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
        Log::info("alerta");
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
