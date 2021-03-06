<?php

namespace App\Http\Controllers\Almacenamiento;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Etiqueta\CreateRequest;
use App\Http\Requests\Etiqueta\UpdateRequest;

use App\Models\Caja;
use App\Models\Lote;
use App\Models\Etiqueta;
use App\Models\Producto;
use App\Models\CajaPosicion;
use App\Models\OrdenProduccion;
use App\Models\OrdenTrabajo;
use App\Models\Frigorifico;
use App\Models\Posicion;
use App\Models\Camara;
use App\Models\Condicion;

class EtiquetaController extends Controller
{
    public function reprint(Request $request)
    {
        /*$etiqueta = Etiqueta::findOrFail($request->etiqueta_id);

        $created_at = new \Carbon\Carbon($etiqueta->created_at);
        $now = \Carbon\Carbon::now();

        $diff_minutes = $created_at->diffInMinutes($now);

        if($diff_minutes >= 5)
        {
            return response()->json(["nok", "Para reemprimir esta Etiqueta debes solicitar autorización a la Administración."]);
        }
        else
        {
            return response()->json(["ok"]);
        }*/
        $etiqueta = Etiqueta::findOrFail($request->etiqueta_id);

        return response()->json(["ok"]);

    }

    public function print_etiqueta($id, $idioma)
    {
        $etiqueta = Etiqueta::findOrFail($id);

        /*$created_at = new \Carbon\Carbon($etiqueta->created_at);
        $now = \Carbon\Carbon::now();

        $diff_minutes = $created_at->diffInMinutes($now);

        if($diff_minutes >= 5)
        {
            return "Para reemprimir esta Etiqueta debes solicitar autorización a Administración";
        }
        else
        {*/
        //dd($etiqueta->caja->);
        $caja = $etiqueta->caja;
        //dd($caja->orden_producto);
        $producto = $caja->orden_producto->producto;
        $orden = $caja->orden_producto->orden;
        $lote = $etiqueta->lote;

        $condicion = $producto->condicion->condicion_name;
        
        $data['condicion']=$condicion;
        $data['fecha_produccion'] = \Carbon\Carbon::createFromFormat('Y-m-d', $etiqueta->etiqueta_fecha)->format('d-m-Y');
        $data['fecha_vencimiento'] = \Carbon\Carbon::createFromFormat('Y-m-d', $lote->lote_fecha_expiracion)->format('d-m-Y');
        $data['especie_comercial_name'] = $producto->especie->especie_comercial_name;
        $data['producto'] = $producto->getFullName();
        $data['nombre'] = $producto->producto_nombre;
        $data['calibre'] = $producto->calibre->calibre_nombre;
        $data['calidad'] = $producto->calidad->calidad_nombre;
        $data['piezas'] = round($caja->caja_unidades);
        $data['peso_neto'] = $caja->caja_peso_real;
        $data['caja_number'] = $number = str_pad($caja->caja_id, 6, 0, STR_PAD_LEFT);
        $data['barcode'] = \DNS1D::getBarcodePNG($etiqueta->etiqueta_barcode, "C128");
        $data['code'] = $etiqueta->etiqueta_barcode;
        $data['lote_number'] = $lote->lote_id;

        if(\Config::get('options.idioma')[$idioma] == "Español")
            $view =  \View::make('almacenamiento.etiqueta.invoice_es',
                compact('data'))->render();
        elseif (\Config::get('options.idioma')[$idioma] == "Inglés")
            $view =  \View::make('almacenamiento.etiqueta.invoice',
                compact('data'))->render();
        else
            $view =  \View::make('almacenamiento.etiqueta.invoice',
                compact('data'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream('invoice', array ("Attachment" => false));
        //}

    }

    public function print_especifica($id, $idioma)
    {
        $etiqueta = Etiqueta::findOrFail($id);

        /*$created_at = new \Carbon\Carbon($etiqueta->created_at);
        $now = \Carbon\Carbon::now();

        $diff_minutes = $created_at->diffInMinutes($now);

        if($diff_minutes >= 5)
        {
            return "Para reemprimir esta Etiqueta debes solicitar autorización a Administración";
        }
        else
        {*/
        //dd($etiqueta->caja->);
        $caja = $etiqueta->caja;
        //dd($caja->orden_producto);
        $producto = $caja->orden_producto->producto;
        $orden = $caja->orden_producto->orden;
        $lote = $etiqueta->lote;

        $condicion = $producto->condicion->condicion_name;
        
        $data['condicion']=$condicion;
        $data['fecha_produccion'] = \Carbon\Carbon::createFromFormat('Y-m-d', $etiqueta->etiqueta_fecha)->format('d-m-Y');
        $data['fecha_vencimiento'] = \Carbon\Carbon::createFromFormat('Y-m-d', $lote->lote_fecha_expiracion)->format('d-m-Y');
        $data['especie_comercial_name'] = $producto->especie->especie_comercial_name;
        $data['producto'] = $producto->getFullName();
        $data['calibre'] = $producto->calibre->calibre_nombre;
        $data['calidad'] = $producto->calidad->calidad_nombre;
        $data['piezas'] = round($caja->caja_unidades);
        $data['peso_neto'] = $caja->caja_peso_real;
        $data['caja_number'] = $number = str_pad($caja->caja_id, 6, 0, STR_PAD_LEFT);
        $data['barcode'] = \DNS1D::getBarcodePNG($etiqueta->etiqueta_barcode, "C128");
        $data['code'] = $etiqueta->etiqueta_barcode;
        $data['lote_number'] = $lote->lote_id;

        if(\Config::get('options.idioma')[$idioma] == "Español")
            $view =  \View::make('almacenamiento.nordic.invoice_es',
                compact('data'))->render();
        elseif (\Config::get('options.idioma')[$idioma] == "Inglés")
            $view =  \View::make('almacenamiento.nordic.invoice',
                compact('data'))->render();
        else
            $view =  \View::make('almacenamiento.nordic.invoice',
                compact('data'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream('invoice', array ("Attachment" => false));
        //}

    }

    public function invoice() 
    {
        $data = $this->getData();
        $date = date('Y-m-d');
        $invoice = "2222";
        $view =  \View::make('almacenamiento.etiqueta.invoice', compact('data', 'date', 'invoice'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('invoice', array ("Attachment" => false));
    }

    public function getData() 
    {
        //$barcode = \DNS1D::getBarcodePNGPath("20160213406005143", "C128");
        //dd('<img src="data:image/png,' . \DNS1D::getBarcodePNGPath("20160213406005142", "C128") . '" alt="barcode"   />');
        $data =  [
            'quantity'      => '1' ,
            'description'   => 'some ramdom text',
            'price'   => '500',
            'total'     => '500',
            'barcode' => \DNS1D::getBarcodePNG("20160213406005143", "C128"),
            'code' => '20160213406005143'
        ];
        return $data;
    }

    public function indexAll(Request $request)
    {
        if($request->ajax())
        {
            $etiquetas = Etiqueta::has('caja.orden_producto.lote')
                ->with('caja.orden_producto.lote')
                ->get();

            if($etiquetas->count() == 0)
            {
                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($etiquetas as $etiqueta) {
                //completo el json
                $dt_json .= '["'.$etiqueta->etiqueta_id.'","'
                    .$etiqueta->lote->lote_id.'","'
                    .$etiqueta->caja->caja_id.'","'
                    .$etiqueta->etiqueta_barcode.'","'
                    .$etiqueta->etiqueta_estado.'","'
                    .\Carbon\Carbon::createFromFormat('Y-m-d',$etiqueta->etiqueta_fecha)->format('d-m-Y').'"],';
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
            return view('almacenamiento.etiqueta.index_all');
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
            $etiquetas = Etiqueta::where('etiqueta_estado', 'NO RECEPCIONADA')
                ->get();

            if($etiquetas->count() == 0)
            {
                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($etiquetas as $etiqueta) {
                //completo el json
                $dt_json .= '["'.$etiqueta->etiqueta_id.'","'
                                .$etiqueta->lote->lote_id.'","'
                                .$etiqueta->caja->caja_id.'","'
                                .$etiqueta->etiqueta_barcode.'","'
                                .$etiqueta->etiqueta_estado.'","'
                                .\Carbon\Carbon::createFromFormat('Y-m-d',$etiqueta->etiqueta_fecha)->format('d-m-Y').'"],';
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
            return view('almacenamiento.etiqueta.index');
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
/*        $etiqueta = Etiqueta::orderBy('created_at', 'DESC')->limit(1)->first();

        $orden = $etiqueta->caja->orden_producto->orden;
        $lote = $orden->lote;

        $productos = $orden->productos->lists('fullName','producto_id');

        $producto = $etiqueta->caja->orden_producto->producto;

        $orden_id = $orden->orden_id;
        $lote_id = $lote->lote_id;
        $producto_id = $producto->producto_id;
        $producto_fullName = $producto->fullName;
        $peso_estandar = $producto->producto_peso;
        $caja_id = $etiqueta->caja->caja_id + 1;

        return view('almacenamiento.etiqueta.create', compact('lote_id', 'orden_id', 'producto_id',
            'producto_fullName', 'caja_id', 'productos', 'peso_estandar'));*/
        
        $proxima_caja = Caja::withTrashed()->max('caja_id') + 1;

        return view('almacenamiento.etiqueta.create',compact('proxima_caja'));

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
        \DB::beginTransaction();

        try{

            $orden = OrdenTrabajo::findOrFail($request->orden_id);


            $info_caja = array(
                'caja_ot_producto_id' => $orden->orden_trabajo_id,
                'caja_peso_real' => $request->peso_real,
                'caja_glaseado' => $request->glaseado,
                'caja_peso_bruto' => $request->peso_bruto,
                'caja_unidades' => $request->unidades
            );

            $caja = Caja::create($info_caja);

            $number = str_pad($caja->caja_id, 6, 0, STR_PAD_LEFT);

            $lote_number =  $request->lote_id;

            $barcode = 'AF0'.\Carbon\Carbon::now()->year.'0'.
                        $lote_number.'0'.
                        $number;

            $info_etiqueta = array(
                'etiqueta_caja_id' => $caja->caja_id,
                'etiqueta_barcode' => $barcode,
                'etiqueta_lote_id' => $request->lote_id,
                'etiqueta_fecha'   => \Carbon\Carbon::createFromFormat('d-m-Y', $request->etiqueta_fecha)
            );

            $etiqueta = Etiqueta::create($info_etiqueta);

            $resp = ["estado" => "ok",
                    "etiqueta_id" => $etiqueta->etiqueta_id];

        }
        catch ( Exception $e ){
            \DB::rollback();
            $resp = ["estado" => "nok"];
        }

        \DB::commit();

        return response()->json($resp);
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
    public function update(UpdateRequest $request)
    {
        //
        if($request->ajax())
        {
            $etiqueta = Etiqueta::where('etiqueta_barcode', $request->etiqueta_barcode)->first();

            if($etiqueta->etiqueta_estado == 'RECEPCIONADA')
            {
                return response()->json(["nok","Esta etiqueta ya fue recepcionada."]);
            }

            \DB::beginTransaction();

            try{
                //se pasa la información a la compañia encontrada
                $etiqueta->etiqueta_estado = 'RECEPCIONADA';
                //se guardan los cambios en la base de datos
                $etiqueta->save();

                $posicion = Posicion::where('posicion_camara_id',$request->select_camara)->first();

                $caja = $etiqueta->caja;
                $caja->caja_posicion()->attach($posicion->posicion_id);
                $caja->input_output()->attach($posicion->posicion_id,
                    ['io_tipo' => 'ENTRADA', 'io_proceso' => 'PRODUCCION']);

                $resp = ["ok"];
            }
            catch ( Exception $e ){
                \DB::rollback();
                $resp = ["nok","Esta etiqueta ya fue recepcionada."];
            }

            \DB::commit();
            
            //se envia respuesta al cliente
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
        //
        if($request->ajax())
        {
            //$etiqueta = Etiqueta::where('etiqueta_barcode', $request->etiqueta_barcode)->first();
            $etiqueta = Etiqueta::findOrFail($request->etiqueta_id);

            if($etiqueta->etiqueta_estado == 'ANULADA')
            {
                return response()->json(["nok","Esta etiqueta ya fue anulada."]);
            }
            \DB::beginTransaction();

            try{
                //se pasa la información a la compañia encontrada
                $etiqueta->etiqueta_estado = 'ANULADA';
                //se guardan los cambios en la base de datos
                $etiqueta->save();

                $etiqueta->delete();

                $caja = $etiqueta->caja;

                if($caja->caja_posicion()->count() > 0)
                {
                    $caja_posicion = CajaPosicion::where('caja_posicion_caja_id', $caja->caja_id)
                                    ->first();
                    $caja->input_output()->attach($caja_posicion->caja_posicion_posicion_id,
                            ['io_tipo' => 'SALIDA', 'io_proceso' => 'PRODUCCION']);
                    $caja_posicion->delete();
                }
                
                $caja->delete();
            }
            catch ( Exception $e ){
                \DB::rollback();
                $resp = ["nok","Esta etiqueta ya fue recepcionada."];
            }

            \DB::commit();

            //se envia respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
