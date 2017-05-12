<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Log;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\OrdenTrabajo\CreateRequest;
use App\Http\Requests\OrdenTrabajo\UpdateRequest;
use App\Http\Requests\OrdenTrabajo\ReturnRequest;

use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoProducto;
use App\Models\Especie;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\OrdenProduccion;
use App\Models\OrdenProduccionProducto;
use App\Models\etiqueta_MP;
use App\Models\Caja;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rec_etiqueta(Request $request){

        Log::info($request->orden);
        Log::info($request->etiqueta_id);

        $et = Etiqueta_MP::where('etiqueta_mp_id',$request->etiqueta_id)->first();

        Log::info($et);

        return response()->json(["estado" => "ok", "eti" => $et]);

    }
    public function cargar_especie(Request $request)
    {
        //
        
        $pro = OrdenProduccionProducto::where('op_producto_orden_id',$request->orden_prod)->with('producto')->get();

        $especies = [];
        $dat = [];

        foreach ($pro as $pp) {

            if($pp->producto->especie->especie_id > 0){

                $dat[$pp->producto->especie->especie_id] = $pp->producto->especie->especie_comercial_name;

                Log::info($pp->producto->especie->especie_id);
                Log::info($pp->producto->especie->especie_comercial_name);

                Log::info($dat);
            }   
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
                ->where('producto_id','<>',0)
                ->lists('producto_fullname','producto_id')

                ->all();

        Log::info($productos);

        return $productos; 
    }

 
    public function kilos_eti(Request $request)
    {
        //



        $eti = Etiqueta_MP::where('etiqueta_mp_estado','RECEPCIONADO')->where('etiqueta_mp_barcode',$request->etiqueta_pallet)
        ->count();



        if($eti == 1){



            $kilos = Etiqueta_MP::where('etiqueta_mp_barcode',$request->etiqueta_pallet)->first();


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
        Log::info($request->etiquetas);

        if($request->ajax()){

            $peso = 0;

            $prod = explode(',',$request->etiquetas);

            $orden_fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->orden_fecha);
            
            for ($x=0; $x < count($prod) ; $x++) { 

                $etiq = Etiqueta_MP::where('etiqueta_mp_barcode',$prod[$x])->firstOrFail();

                Log::info($etiq);

                $peso = $peso + $etiq->etiqueta_mp_peso;
                
                Log::info($peso);
            }

            $info = array(
                'orden_trabajo_orden_produccion'    => $request->orden_trabajo_orden_produccion,
                'orden_trabajo_especie'           => $request->orden_trabajo_especie,
                'orden_trabajo_producto'    => $request->orden_trabajo_producto,
                'orden_trabajo_fecha'=> $orden_fecha,
                'orden_trabajo_peso_total'      => $peso
            );


            $orden = OrdenTrabajo::create($info);


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
    public function show(Request $request)
    {
        

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

        $nombre_especie = Especie::where('especie_id',$orden->orden_trabajo_especie)->get()->lists('especie_comercial_name');

        Log::info($nombre_especie);

        $nombre_producto = Producto::where('producto_id',$orden->orden_trabajo_producto)->get()->lists('fullName');

        Log::info($nombre_producto);

        $repl = array('[',']','"','"');

        if($request->ajax())
        {
            $especies = array($orden->orden_trabajo_especie => str_replace($repl," ",$nombre_especie));
                
            $productos = array($orden->orden_trabajo_producto => str_replace($repl," ",$nombre_producto));

            $orden_producto = array($orden->orden_trabajo_orden_produccion => $orden->orden_trabajo_orden_produccion);

            $orden_fecha = \Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_trabajo_fecha)->format('d-m-Y');

            $op = OrdenTrabajoProducto::where('ot_producto_orden_trabajo',$request->orden_id)->with('etiqueta')->get();

            $info = [];

            foreach ($op as $eti) {
                
                Log::info($eti->etiqueta);
                
                $info[] = $eti->etiqueta;  

            }

            Log::info($info);
            Log::info($especies);
            Log::info($productos);
            Log::info($orden_producto);
            Log::info($orden_fecha);

            $view = \View::make('admin.orden_trabajo.fields')
                    ->with('ordenProduccion', $orden_producto)
                    ->with('proxima_orden', $request->orden_id)
                    ->with('orden_trabajo_fecha', $orden_fecha)
                    ->with('especies',$especies)
                    ->with('productos',$productos);

            $sections = $view->renderSections();
              
            return response()->json(["estado" => "ok", "pallet" => $info, "section" => $sections['contentPanel']]);
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

            $peso = 0;
            $prod = explode(',',$request->etiquetas);

            for ($i=0; $i < count($prod) ; $i++) { 
               
                Log::info($prod[$i]);

                $etiq = Etiqueta_MP::where('etiqueta_mp_barcode',$prod[$i])->firstOrFail();

                $peso = $peso + $etiq->etiqueta_mp_peso;

                $pp = array(
                    'ot_producto_orden_trabajo' => $request->orden_number,
                    'ot_producto_etiqueta_pallet' => $etiq->etiqueta_mp_id
                );

                $opp = OrdenTrabajoProducto::create($pp);

                $otp = OrdenTrabajo::where('orden_trabajo_id',$request->orden_number)->firstOrFail();

                $inf = array('orden_trabajo_peso_total' =>$peso);

                $otp->fill($inf);
                $otp->save();

                $etiqueta = Etiqueta_MP::where('etiqueta_mp_barcode',$prod[$i])->firstOrFail();

                $info = array('etiqueta_mp_estado' => 'PRODUCCION');
                $etiqueta->fill($info);
                $etiqueta->save();   
            }
        
            //envio respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }

    public function r_pallet(ReturnRequest $request)
    {
        //

        Log::info($request->orden_etid);
        Log::info($request->orden_number);
        Log::info($request->orden_kilos_actual);
        Log::info($request->orden_kilos_retornar);
        Log::info($request->orden_cajas_actual);
        Log::info($request->orden_cajas_retornar);

        $ot = OrdenTrabajo::where('orden_trabajo_id',$request->orden_number)->firstOrFail();

        $r_kilos = $request->orden_kilos_actual -  $request->orden_kilos_retornar;
        $r_cajas = $request->orden_cajas_actual - $request->orden_cajas_retornar;

        if($r_cajas < 0 || $r_kilos < 0){
            Log::info("error");
            return response()->json(['estado' => "nok", 'id' => 0]);
        }

        if($request->ajax())
        {

            if( $r_cajas == 0 && $r_kilos == 0){

                $opp = OrdenTrabajoProducto::where('ot_producto_orden_trabajo',$request->orden_number)->where('ot_producto_etiqueta_pallet',$request->orden_etid)->firstOrFail();

                $opp->delete();       

                $etiqueta = Etiqueta_MP::where('etiqueta_mp_id',$request->orden_etid)->firstOrFail();

                $peso = $ot->orden_trabajo_peso_total - $etiqueta->etiqueta_mp_peso;

                $sot = OrdenTrabajo::where('orden_trabajo_id',$request->orden_number)->firstOrFail();

                $inf = array('orden_trabajo_peso_total' => $peso);
                $sot->fill($inf);
                $sot->save();

                $info = array('etiqueta_mp_estado' => 'NO RECEPCIONADO');
                $etiqueta->fill($info);
                $etiqueta->save();

                return response()->json(['estado' => "ok", 'id' => 0]);
            }
            else{

                $etiqueta = Etiqueta_MP::where('etiqueta_mp_id',$request->orden_etid)->firstOrFail();

                $info_etiqueta_mp = array(
                    'etiqueta_mp_lote_id'           => $etiqueta->etiqueta_mp_lote_id,
                    'etiqueta_mp_estado'            => '1',
                    'etiqueta_mp_producto_id'       => $etiqueta->etiqueta_mp_producto_id,
                    'etiqueta_mp_fecha'             => \Carbon\Carbon::createFromFormat('Y-m-d', $etiqueta->etiqueta_mp_fecha),
                    'etiqueta_mp_barcode'           => 'a',
                    'etiqueta_mp_peso'              => $request->orden_kilos_retornar,
                    'etiqueta_mp_cantidad_cajas'    => $request->orden_cajas_retornar
                );

                $eti_id=Etiqueta_MP::create($info_etiqueta_mp);
                $etiqueta_mp= Etiqueta_MP::findOrFail($eti_id->etiqueta_mp_id);

                $barcode = 'PMP'.$etiqueta->etiqueta_mp_lote_id.'0'.$eti_id->etiqueta_mp_id.'0P';

                $info = array(
                    'etiqueta_mp_barcode'           => $barcode
                );

                $etiqueta_mp -> fill($info);
                $etiqueta_mp->save();


                $eti_dos = Etiqueta_MP::where('etiqueta_mp_id',$request->orden_etid)->firstOrFail();

                $enfo = array(

                    'etiqueta_mp_peso'              => $r_kilos,
                    'etiqueta_mp_cantidad_cajas'    => $r_cajas
                );

                $eti_dos -> fill($enfo);
                $eti_dos->save();

                $peso = $ot->orden_trabajo_peso_total - $request->orden_kilos_retornar;

                $sot = OrdenTrabajo::where('orden_trabajo_id',$request->orden_number)->firstOrFail();

                $inf = array('orden_trabajo_peso_total' => $peso);
                $sot->fill($inf);
                $sot->save();


                return response()->json(['estado' => "ok", 'id' => $eti_id->etiqueta_mp_id]);

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
        //
        if($request->ajax())
        {          
            $orden = OrdenTrabajo::findOrFail($request->orden_number);

            $orden->delete();

            $ot = OrdenTrabajoProducto::where('ot_producto_orden_trabajo',$request->orden_number)->findOrFail();

            $ot->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }

    public function pre_borrado(Request $request)
    {
        //
        if($request->ajax()){
            Log::info($request->id);
            
            $caj = Caja::where('caja_ot_producto_id',$request->id)->count();

            Log::info($caj);

            if($caj == 0){

                $orden = OrdenTrabajo::where('orden_trabajo_id',$request->id)->firstOrFail();

                $nombre_especie = Especie::where('especie_id',$orden->orden_trabajo_especie)->get()->lists('especie_comercial_name');

        
                $nombre_producto = Producto::where('producto_id',$orden->orden_trabajo_producto)->get()->lists('fullName');

                $repl = array('[',']','"','"');

                $especies = array($orden->orden_trabajo_especie => str_replace($repl," ",$nombre_especie));
                
                $productos = array($orden->orden_trabajo_producto => str_replace($repl," ",$nombre_producto));

                $orden_producto = array($orden->orden_trabajo_orden_produccion => $orden->orden_trabajo_orden_produccion);

                $orden_fecha = \Carbon\Carbon::createFromFormat('Y-m-d',$orden->orden_trabajo_fecha)->format('d-m-Y');

                $op = OrdenTrabajoProducto::where('ot_producto_orden_trabajo',$request->id)->with('etiqueta')->get();

                $info = [];

                foreach ($op as $eti) {
                    
                    Log::info($eti->etiqueta);
                    
                    $info[] = $eti->etiqueta;  

                }

                Log::info($info);
                Log::info($especies);
                Log::info($productos);
                Log::info($orden_producto);
                Log::info($orden_fecha);

                $view = \View::make('admin.orden_trabajo.fields')
                        ->with('ordenProduccion', $orden_producto)
                        ->with('proxima_orden', $request->id)
                        ->with('orden_trabajo_fecha', $orden_fecha)
                        ->with('especies',$especies)
                        ->with('productos',$productos);

                $sections = $view->renderSections();
                  
                return response()->json(["estado" => "ok", "pallet" => $info, "section" => $sections['contentPanel']]);
            }        
        }
        
    }
}
