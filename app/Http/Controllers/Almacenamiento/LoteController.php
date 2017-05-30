<?php

namespace App\Http\Controllers\Almacenamiento;

use App\Models\Calidad;
use App\Models\Formato;
use Illuminate\Http\Request;
use Log;


use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Lote\CreateRequest;
use App\Http\Requests\Lote\UpdateRequest;

use App\Models\Procesador;
use App\Models\MateriaPrima;
use App\Models\Elaborador;
use App\Models\Especie;
use App\Models\Condicion;
use App\Models\Productor;
use App\Models\Destino;
use App\Models\Lote;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoProducto;
use App\Models\Etiqueta_MP;




class LoteController extends Controller
{
    public function next(Request $request)
    {
        if($request->ajax())
        {
            $proximo_lote = Lote::withTrashed()->max('lote_id') + 1;

            return response()->json($proximo_lote);
        }
    }

    public function lotes_produccion(Request $request)
    {
        if($request->ajax())
        {
            $lotes = Lote::all();

            $no_produccion = $lotes->where('lote_produccion', 'NO')
                            ->lists('lote_id', 'lote_id');
            $produccion = $lotes->where('lote_produccion', 'SI')
                            ->lists('lote_id', 'lote_id');

            $view = \View::make('almacenamiento.lote.produccionfields')
                    ->with('produccion', $produccion)
                    ->with('no_produccion', $no_produccion);

            $sections = $view->renderSections();

            return response()->json(["ok",$sections['contentPanel']]);
        }
    }

    public function lote_change(Request $request)
    {
        if($request->ajax())
        {
            $lote = Lote::findOrFail($request->lote_id);
            $lotus = Lote::findOrFail($request->lote_id);

            if($request->action == 1)
            {
                if($lotus->lote_djurada == "NO"){

                    $lote->lote_produccion = 'SI';
                    $lote->lote_reestriccion = 'SI';
                    $lote->lote_observaciones = 'SOLO MERCADO NACIONAL';

                    $lote->save();

                    return response()->json(["OK",1]);    
                }
                else{

                    $lote->lote_produccion = 'SI';

                    $lote->save();

                    return response()->json(["OK",0]);    
                }           
                
            }
            else if($request->action == 0)
            {

                if($lotus->lote_djurada == "NO"){

                    $lote->lote_produccion = 'NO';
                    $lote->lote_reestriccion = 'NO';
                    $lote->lote_observaciones = ''; 
                    
                    $lote->save();

                    return response()->json("OK");

                }else{

                    $lote->lote_produccion = 'NO';

                    $lote->save();

                    return response()->json("OK");
                }                
            }
        }
    }

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

            echo $request->id;
            if($request->q == "etiqueta" & empty($request->orden_id) )
            {
                $lotes = Lote::where('lote_produccion', 'SI')
                            ->has('orden_produccion')
                            ->with('procesador','productor')->get();
            }
            else if($request->q == "etiqueta" & !empty($request->orden_id))
            {


                $lotes = \DB::table('lote')
                            ->join('procesador','lote.lote_procesador_id','=','procesador.procesador_id')
                            ->join('productor','lote.lote_productor_id','=','productor.productor_id')
                            ->join('etiqueta_mp','lote.lote_id','=','etiqueta_mp.etiqueta_mp_lote_id')
                            ->join('ot_producto','etiqueta_mp.etiqueta_mp_id','=','ot_producto.ot_producto_etiqueta_pallet')
                            ->join('orden_trabajo','ot_producto.ot_producto_orden_trabajo','=','orden_trabajo_id')
                            ->select('lote.*','procesador.*','productor.*')
                            ->where('orden_trabajo.orden_trabajo_id',$request->orden_id)
                            ->distinct()
                            ->get();  

                if(empty($lotes))
                    {
                        return '{"data":[]}'; 
                    }
                    //inicializo el json
                    $dt_json = '{ "data" : [';

                    //para cada compañia
                    foreach ($lotes as $lote) {
                        //completo el json
                        $dt_json .= '["","'
                                        .$lote->lote_id.'","'
                                        .$lote->lote_kilos_recepcion.'","'
                                        .$lote->lote_n_documento.'","'
                                        .$lote->procesador_name.'","'
                                        .$lote->productor_name.'","'
                                        .$lote->lote_djurada.'","'
                                        .$lote->lote_produccion.'"],';
                    }
                    //elimino la ultima coma del json
                    $dt_json = substr($dt_json, 0, -1);
                    //se cierra el json
                    $dt_json.= "] }";
                    //envio respuesta al cliente
                    return $dt_json;                      

            }
            else if($request->q == "etiquetamp")
            {
                $lotes = Lote::with('procesador','productor','especie')->get();
            }
            else if($request->q == "despacho")
            {
                $lotes = Lote::has('orden_produccion.cajas.caja_posicion')
                            ->with('procesador','productor')->get();
            }
            else
            {
                $lotes = Lote::with('procesador','productor')->get();
            }

            if($lotes->count() == 0)
            {
                return '{"data":[]}'; 
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($lotes as $lote) {
                //completo el json
                $dt_json .= '["","'
                                .$lote->lote_id.'","'
                                .$lote->lote_n_documento.'","'
                                .$lote->procesador->procesador_name.'","'
                                .$lote->productor->productor_name.'","'
                                .$lote->lote_djurada.'","'
                                .$lote->lote_produccion.'","'
                                .$lote->lote_kilos_recepcion.'","'
                                .$lote->especie->especie_id.'","'
                                .$lote->especie->especie_comercial_name.'"],';
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
            return view('almacenamiento.lote.index');
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
        $procesadores = [''=>'Ninguno'] + 
                        Procesador::orderBy('procesador_name', 'ASC')
                        ->get()
                        ->lists('procesador_name','procesador_id')
                        ->all();

        $calidades = ['null' => 'Ninguno'] + Calidad::orderBy('calidad_nombre', 'ASC')
                ->where('calidad_id','>','0')
                ->get()
                ->lists('calidad_nombre', 'calidad_id')->all();

        $formatos = ['null'=>'Ninguno'] + Formato::orderBy('formato_nombre', 'ASC')
                ->where('formato_id','>','0')
                ->get()
                ->lists('formato_abreviatura','formato_id')->all();

        $condiciones = ['null'=>'Ninguna'] + Condicion::orderBy('condicion_name', 'ASC')
                ->where('condicion_id','>','0')
                ->get()
                ->lists('condicion_name','condicion_id')->all();
        

        $mps = ['' => 'Ninguno'] +
                       MateriaPrima::orderBy('materia_prima_name', 'ASC')
                        ->orderBy('materia_prima_name', 'ASC')
                        ->get()
                        ->lists('materia_prima_name','materia_prima_id')
                        ->all();

        $elaboradores = [''=>'Ninguno'] + 
                        Elaborador::orderBy('elaborador_name', 'ASC')
                        ->get()
                        ->lists('elaborador_name','elaborador_id')
                        ->all();

        $especies = [''=>'Ninguno'] + 
                        Especie::orderBy('especie_name', 'ASC')
                        ->where('especie_id','>','0')
                        ->get()
                        ->lists('especie_comercial_name','especie_id')
                        ->all();

        $productores = [''=>'Ninguno'] + 
                        Productor::orderBy('productor_name', 'ASC')
                        ->get()
                        ->lists('productor_name','productor_id')
                        ->all();

        $destinos = [''=>'Ninguno'] + 
                        Destino::orderBy('destino_name', 'ASC')
                        ->get()
                        ->lists('destino_name','destino_id')
                        ->all();

        $clientes = [''=>'Ninguno'] + 
                        Cliente::orderBy('cliente_nombre', 'ASC')
                        ->get()
                        ->lists('cliente_nombre','cliente_id')
                        ->all();


        if(is_null(Lote::withTrashed()->max('lote_id')))
            $proximo_lote = env('PRIMER_LOTE');
        else
            $proximo_lote = Lote::withTrashed()->max('lote_id') + 1;
        
        $fecha_documento = \Carbon\Carbon::now()->format('d-m-Y');
        $fecha_planta = \Carbon\Carbon::now()->format('d-m-Y');
        $fecha_expiracion = \Carbon\Carbon::now()->format('d-m-Y');

        return view('almacenamiento.lote.lote', 
                compact('procesadores',
                        'calidades',
                        'formatos',
                        'elaboradores',
                        'condiciones',
                        'especies',
                        'mps',
                        'productores',
                        'destinos', 
                        'proximo_lote',
                        'fecha_documento',
                        'fecha_planta',
                        'fecha_expiracion',
                        'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        //si la peticion es ajax
        if($request->ajax())
        {
            $fecha_documento = \Carbon\Carbon::createFromFormat('d-m-Y', $request->lote_fecha_documento)->format('Y-m-d');
            $fecha_planta = \Carbon\Carbon::createFromFormat('d-m-Y', $request->lote_fecha_planta)->format('Y-m-d');
            $fecha_expiracion = \Carbon\Carbon::createFromFormat('d-m-Y', $request->lote_fecha_expiracion)->format('Y-m-d');

            $info = array(
                'lote_year'             => $request->lote_year,
                'lote_number'           => $request->lote_number,
                'lote_procesador_id'    => $request->lote_procesador_id,
                'lote_elaborador_id'    => $request->lote_elaborador_id,
                'lote_mp_id'            => $request->lote_mp_id,
                'lote_especie_id'       => $request->lote_especie_id,
                'lote_calidad_id'       => $request->lote_calidad_id,
                'lote_fecha_documento'  => $fecha_documento,
                'lote_fecha_planta'     => $fecha_planta,
                'lote_fecha_expiracion' => $fecha_expiracion,
                'lote_n_documento'      => $request->lote_n_documento,
                'lote_kilos_declarado'  => $request->lote_kilos_declarado,
                'lote_kilos_recepcion'  => 0,
                'lote_cajas_declarado'  => $request->lote_cajas_declarado,
                'lote_cajas_recepcion'  => 0,
                'lote_productor_id'     => $request->lote_productor_id,
                'lote_destino_id'       => $request->lote_destino_id,
                'lote_cliente_id'       => $request->lote_cliente_id,
                'lote_condicion_id'     => $request->lote_condicion_id,
                'lote_users_id'         => \Auth::user()->users_id,
                'lote_observaciones'    => $request->lote_observaciones,
                'lote_djurada'          => \Config::get('options.djurada')[$request->lote_djurada],
                'lote_reestriccion'     => \Config::get('options.reestriccion')[$request->lote_reestriccion],
                'lote_tipo_id'          => $request->lote_tipo_id
                );
            //creo y guardo una compañia con toda la informacion enviada
            Lote::create($info);
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
        //
        if($request->ajax())
        {
            $lote = Lote::findOrFail($request->lote_id);

            if($request->q == "despacho")
            {
                $resp = [];

                $productos = $lote->getProducts();

                $arr_productos = [];
                foreach ($productos as $producto) {
                    # code...
                    $arr_productos [$producto->producto_id] = $producto->fullName;
                }

                $resp['lote_id'] = $lote->lote_id;
                $resp['lote_productos'] = $arr_productos;

                return $resp;
            }
            else
            {
                $resp = [];

                $resp['lote_id'] = $lote->lote_id;
                $resp['lote_fecha_documento'] = \Carbon\Carbon::createFromFormat('Y-m-d',$lote->lote_fecha_documento)->format('d-m-Y');
                $resp['lote_fecha_planta'] = \Carbon\Carbon::createFromFormat('Y-m-d',$lote->lote_fecha_planta)->format('d-m-Y');
                $resp['lote_fecha_expiracion'] = \Carbon\Carbon::createFromFormat('Y-m-d',$lote->lote_fecha_documento)->format('d-m-Y');
                $resp['lote_kilos_declarado'] = $lote->lote_kilos_declarado;
                $resp['lote_kilos_recepcion'] = $lote->lote_kilos_recepcion;
                $resp['lote_cajas_declarado'] = $lote->lote_cajas_declarado;
                $resp['lote_cajas_recepcion'] = $lote->lote_cajas_recepcion;
                $resp['lote_djurada'] = $lote->lote_djurada;
                $resp['lote_reestriccion'] = $lote->lote_reestriccion;
                $resp['lote_observaciones'] = $lote->lote_observaciones;
                $resp['lote_tipo'] = \Config::get('options.lote_tipo')[$lote->lote_tipo_id];

                $resp['caja_number'] = Caja::withTrashed()->max('caja_id')+1;

                /*if($lote->orden_produccion()->count() > 0)
                {
                    $resp['orden_id'] = $lote->orden_produccion->orden_id;
                    $resp['orden_number'] = $lote->orden_produccion->orden_number;
                    $resp['orden_descripcion'] = $lote->orden_produccion->orden_descripcion;
                    $resp['orden_productos'] = $lote->orden_produccion->productos()
                                                ->get()
                                                ->lists('producto_nombre', 'producto_id')
                                                ->all();
                    
                }*/
                
                return $resp;
            }
            
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
        if($request->ajax())
        {  
            //busco la compañia a consultar
            $lote = Lote::findOrFail($request->lote_id);

            $procesadores = [''=>'Ninguno'] + 
                        Procesador::orderBy('procesador_name', 'ASC')
                        ->get()
                        ->lists('procesador_name','procesador_id')
                        ->all();

            $calidades = ['null' => 'Ninguno'] + Calidad::orderBy('calidad_nombre', 'ASC')
                    ->where('calidad_id','>','0')
                    ->get()
                    ->lists('calidad_nombre', 'calidad_id')->all();

            $formatos = ['null'=>'Ninguno'] + Formato::orderBy('formato_nombre', 'ASC')
                    ->where('formato_id','>','0')
                    ->get()
                    ->lists('formato_abreviatura','formato_id')->all();

            $mps = ['' => 'Ninguno'] +
                           MateriaPrima::orderBy('materia_prima_name', 'ASC')
                            ->orderBy('materia_prima_name', 'ASC')
                            ->get()
                            ->lists('materia_prima_name','materia_prima_id')
                            ->all();

            $elaboradores = [''=>'Ninguno'] + 
                            Elaborador::orderBy('elaborador_name', 'ASC')
                            ->get()
                            ->lists('elaborador_name','elaborador_id')
                            ->all();

            $especies = [''=>'Ninguno'] + 
                            Especie::orderBy('especie_name', 'ASC')
                            ->where('especie_id','>','0')
                            ->get()
                            ->lists('especie_comercial_name','especie_id')
                            ->all();

            $condiciones = ['null'=>'Ninguna'] + Condicion::orderBy('condicion_name', 'ASC')
                            ->where('condicion_id','>','0')
                            ->get()
                            ->lists('condicion_name','condicion_id')->all();

            $productores = [''=>'Ninguno'] + 
                            Productor::orderBy('productor_name', 'ASC')
                            ->get()
                            ->lists('productor_name','productor_id')
                            ->all();

            $destinos = [''=>'Ninguno'] + 
                            Destino::orderBy('destino_name', 'ASC')
                            ->get()
                            ->lists('destino_name','destino_id')
                            ->all();

            $clientes = [''=>'Ninguno'] + 
                        Cliente::orderBy('cliente_nombre', 'ASC')
                        ->get()
                        ->lists('cliente_nombre','cliente_id')
                        ->all();

            $view = \View::make('almacenamiento.lote.fields')
                    ->with('procesadores', $procesadores)
                    ->with('formatos', $formatos)
                    ->with('calidades', $calidades)
                    ->with('mps', $mps)
                    ->with('condiciones',$condiciones)
                    ->with('elaboradores', $elaboradores)
                    ->with('especies', $especies)
                    ->with('productores', $productores)
                    ->with('clientes', $clientes)
                    ->with('destinos', $destinos)
                    ->with('proximo_lote', $lote->lote_id)
                    ->with('fecha_documento',\Carbon\Carbon::createFromFormat('Y-m-d',$lote->lote_fecha_documento)->format('d-m-Y'))
                    ->with('fecha_planta', \Carbon\Carbon::createFromFormat('Y-m-d',$lote->lote_fecha_planta)->format('d-m-Y'))
                    ->with('fecha_expiracion', \Carbon\Carbon::createFromFormat('Y-m-d',$lote->lote_fecha_expiracion)->format('d-m-Y'));

            $sections = $view->renderSections();

            return response()->json(["estado" => "ok","lote" => $lote,"section" => $sections['contentPanel']]);
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
            $lote = Lote::findOrFail($request->lote_id);

            //se crea un array con la información enviada desde el cliente
            $fecha_documento = \Carbon\Carbon::createFromFormat('d-m-Y', $request->lote_fecha_documento)->format('Y-m-d');
            $fecha_planta = \Carbon\Carbon::createFromFormat('d-m-Y', $request->lote_fecha_planta)->format('Y-m-d');
            $fecha_expiracion = \Carbon\Carbon::createFromFormat('d-m-Y', $request->lote_fecha_expiracion)->format('Y-m-d');

            $info = array(
                'lote_year'             => $request->lote_year,
                'lote_number'           => $request->lote_number,
                'lote_procesador_id'    => $request->lote_procesador_id,
                'lote_elaborador_id'    => $request->lote_elaborador_id,
                'lote_mp_id'            => $request->lote_mp_id,
                'lote_especie_id'       => $request->lote_especie_id,
                'lote_calidad_id'       => $request->lote_calidad_id,
                'lote_fecha_documento'  => $fecha_documento,
                'lote_fecha_planta'     => $fecha_planta,
                'lote_fecha_expiracion' => $fecha_expiracion,
                'lote_n_documento'      => $request->lote_n_documento,
                'lote_kilos_declarado'  => $request->lote_kilos_declarado,
                'lote_kilos_recepcion'  => $request->lote_kilos_recepcion,
                'lote_cajas_declarado'  => $request->lote_cajas_declarado,
                'lote_cajas_recepcion'  => $request->lote_cajas_recepcion,
                'lote_productor_id'     => $request->lote_productor_id,
                'lote_destino_id'       => $request->lote_destino_id,
                'lote_cliente_id'       => $request->lote_cliente_id,
                'lote_condicion_id'     => $request->lote_condicion_id,
                'lote_users_id'         => \Auth::user()->users_id,
                'lote_observaciones'    => $request->lote_observaciones,
                'lote_djurada'          => \Config::get('options.djurada')[$request->lote_djurada],
                'lote_reestriccion'     => \Config::get('options.reestriccion')[$request->lote_reestriccion],
                'lote_tipo_id'          => $request->lote_tipo_id
                );

            //se pasa la información a la compañia encontrada
            $lote->fill($info);
            //se guardan los cambios en la base de datos
            $lote->save();
            //se envia respuesta al cliente
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
    public function destroy(Request $request)
    {
        //
        if($request->ajax())
        {
            //se crea el validador con la información enviada desde el cliente
            //y con las reglas de validación respectiva
            $v = \Validator::make($request->all(), [
                'lote_id' => 'required|exists:lote,lote_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la compañia a eliminar
            $lote = Lote::findOrFail($request->lote_id);
            //elimino la compañia
            $lote->delete();
            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
