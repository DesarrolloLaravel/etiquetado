<?php

namespace App\Http\Controllers\Almacenamiento;

use Illuminate\Http\Request;
use Illuminate\Console\Command;


use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\EtiquetaMP\CreateRequest;
use App\Http\Requests\EtiquetaMP\UpdateRequest;

use App\Models\Caja;
use App\Models\Lote;
use App\Models\Etiqueta_MP;
use App\Models\Producto;
use App\Models\CajaPosicion;
use App\Models\Camara;
use App\Models\Frigorifico;
use App\Models\OrdenProduccion;

class EtiquetaMPController extends Controller
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

    public function print_etiqueta($id)
    {
        $etiqueta_mp = Etiqueta_MP::findOrFail($id);

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


        $lote = $etiqueta_mp->lote;
        $producto= $etiqueta_mp->producto;


        $data['fecha_produccion'] = \Carbon\Carbon::createFromFormat('Y-m-d', $etiqueta_mp->etiqueta_mp_fecha)->format('d-m-Y');
        $data['fecha_vencimiento'] = \Carbon\Carbon::createFromFormat('Y-m-d', $lote->lote_fecha_expiracion)->format('d-m-Y');
        $data['especie_comercial_name'] = $producto->especie->especie_comercial_name;
        $data['producto'] = $producto->getFullName("Español");
        $data['calibre'] = $producto->calibre->calibre_nombre;
        $data['calidad'] = $producto->calidad->calidad_nombre;
        $data['piezas'] = round($etiqueta_mp->etiqueta_mp_cantidad_cajas);
        $data['peso_neto'] = $etiqueta_mp->etiqueta_mp_peso;
        $data['pallet_number'] = $etiqueta_mp->etiqueta_mp_id;
        $data['barcode'] = \DNS1D::getBarcodePNG($etiqueta_mp->etiqueta_mp_barcode, "C128");
        $data['code'] = $etiqueta_mp->etiqueta_mp_barcode;
        $data['lote_number'] = $lote->lote_id;

            $view =  \View::make('almacenamiento.etiqueta_mp.invoice_es',
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
            $etiquetas = Etiqueta::has('caja.orden_producto.orden.lote')
                ->with('caja.orden_producto.orden.lote')
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
                    .$etiqueta->caja->orden_producto->orden->lote->lote_id.'","'
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
            $etiquetas_mp = Etiqueta_MP::all();


            if($etiquetas_mp->count() == 0)
            {
                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($etiquetas_mp as $etiqueta_mp) {
                //completo el json
                $dt_json .= '["'.$etiqueta_mp->etiqueta_mp_id.'","'
                                .$etiqueta_mp->lote->lote_id.'","'
                                .$etiqueta_mp->producto->getFullName().'","'
                                .$etiqueta_mp->etiqueta_mp_barcode.'","'
                                .$etiqueta_mp->etiqueta_mp_estado.'","'                                          
                                .\Carbon\Carbon::createFromFormat('Y-m-d',$etiqueta_mp->etiqueta_mp_fecha)->format('d-m-Y').'"],';
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
            return view('almacenamiento.etiqueta_mp.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $productos =[''=>'Ninguno'];

        return view('almacenamiento.etiqueta_mp.create',compact('productos'));

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

            $barcode = 'PMP'.$request->etiqueta_fecha.'0'.$request->lote_id.'0'.'P';

            $info_etiqueta_mp = array(
                'etiqueta_mp_lote_id'           => $request->lote_id,
                'etiqueta_mp_estado'            => '1',
                'etiqueta_mp_producto_id'       => $request->orden_productos_id,
                'etiqueta_mp_fecha'             => \Carbon\Carbon::createFromFormat('d-m-Y', $request->etiqueta_fecha),
                'etiqueta_mp_barcode'           => $barcode,
                'etiqueta_mp_peso'              => $request->peso_real,
                'etiqueta_mp_cantidad_cajas'    => $request->unidades
            );

            $etiqueta_mp_id=Etiqueta_MP::create($info_etiqueta_mp);
            $etiqueta_mp= Etiqueta_MP::findOrFail($etiqueta_mp_id->etiqueta_mp_id);

            $barcode = 'PMP'.$request->lote_id.'0'.$etiqueta_mp_id->etiqueta_mp_id.'0P';

            $info_etiqueta_mp = array(
                'etiqueta_mp_lote_id'           => $request->lote_id,
                'etiqueta_mp_estado'            =>  '1',                
                'etiqueta_mp_producto_id'       => $request->orden_productos_id,
                'etiqueta_mp_fecha'             => \Carbon\Carbon::createFromFormat('d-m-Y', $request->etiqueta_fecha),
                'etiqueta_mp_barcode'           => $barcode,
                'etiqueta_mp_peso'              => $request->peso_real,
                'etiqueta_mp_cantidad_cajas'    => $request->unidades
            );

            $etiqueta_mp -> fill($info_etiqueta_mp);
            $etiqueta_mp->save();


            $resp = ["estado" => "ok","etiqueta_mp_id" =>$etiqueta_mp_id->etiqueta_mp_id];

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
            $etiqueta_mp = Etiqueta_MP::where('etiqueta_mp_barcode', $request->etiqueta_barcode)->first();

            if($etiqueta_mp->etiqueta_mp_estado == 'RECEPCIONADO' )
            {
                return response()->json(["nok","Este Pallet ya fue recepcionado."]);

            }else if ( $etiqueta_mp->etiqueta_mp_estado == 'PRODUCCIÓN'){

                return response()->json(["nok","Este Pallet está en producción."]);

            }

            \DB::beginTransaction();

            try{
                //se pasa la información a la compañia encontrada
                $etiqueta_mp->etiqueta_mp_estado = 'RECEPCIONADO';
                $etiqueta_mp->etiqueta_mp_posicion = $request->select_camara;
                //se guardan los cambios en la base de datos
                $etiqueta_mp->save();


                $lote = Lote::findOrFail($etiqueta_mp->etiqueta_mp_lote_id);
                $lote->lote_kilos_recepcion=$lote->lote_kilos_recepcion+$etiqueta_mp->etiqueta_mp_peso;
                $lote->lote_cajas_recepcion=$lote->lote_cajas_recepcion+$etiqueta_mp->etiqueta_mp_cantidad_cajas;
                $lote->save();

                $resp = ["ok","Pallet recepcionado exitosamente."];
            }
            catch ( Exception $e ){
                \DB::rollback();
                $resp = ["nok","Este Pallet ya fue recepcionado."];
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
