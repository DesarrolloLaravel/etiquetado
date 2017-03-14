<?php

namespace App\Http\Controllers\Empaque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Etiqueta\CreateRequest;
use App\Http\Requests\Etiqueta\UpdateRequest;

use App\Models\Caja;
use App\Models\Lote;
use App\Models\Etiqueta;
use App\Models\Producto;

class EtiquetaController extends Controller
{
    public function reprint(Request $request)
    {
        $etiqueta = Etiqueta::findOrFail($request->etiqueta_id);

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
        }

    }

    public function print_etiqueta($id)
    {
        $etiqueta = Etiqueta::find($id);

        $created_at = new \Carbon\Carbon($etiqueta->created_at);
        $now = \Carbon\Carbon::now();

        $diff_minutes = $created_at->diffInMinutes($now);

        if($diff_minutes >= 5)
        {
            return "Para reemprimir esta Etiqueta debes solicitar autorización a Administración";
        }
        else
        {
            $caja = $etiqueta->caja;
            $producto = $caja->producto;
            $orden = $caja->orden;
            $lote = $orden->lote;

            $data['fecha_produccion'] = \Carbon\Carbon::now()->format('d-m-Y');
            $data['fecha_vencimiento'] = \Carbon\Carbon::createFromFormat('Y-m-d', $lote->lote_fecha_expiracion)->format('d-m-Y');
            $data['especie'] = $lote->especie->especie_name;
            $data['producto'] = $producto->producto_nombre;
            $data['calibre'] = \Config::get('options.calibre')[$producto->producto_calibre_id];
            $data['calidad'] = \Config::get('options.calidad')[$producto->producto_calidad_id];
            $data['piezas'] = $caja->caja_unidades;
            $data['peso_neto'] = $caja->caja_peso_bruto;
            $data['caja_number'] = $caja->caja_number;
            $data['barcode'] = \DNS1D::getBarcodePNG($etiqueta->etiqueta_barcode, "C128");
            $data['code'] = $etiqueta->etiqueta_barcode;
            $data['lote_number'] = $lote->lote_number;

            $view =  \View::make('empaque.etiqueta.invoice', 
                        compact('data'))->render();

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view);

            return $pdf->stream('invoice', array ("Attachment" => false));
        }

    }

    public function invoice() 
    {
        $data = $this->getData();
        $date = date('Y-m-d');
        $invoice = "2222";
        $view =  \View::make('empaque.etiqueta.invoice', compact('data', 'date', 'invoice'))->render();
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $etiquetas = Etiqueta::all();

        if($request->ajax())
        {
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
                                .$etiqueta->caja->orden->lote->lote_id.'","'
                                .$etiqueta->caja->caja_number.'","'
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
            return view('empaque.etiqueta.index', compact('etiquetas'));
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
        return view('empaque.etiqueta.create');
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
            $lote = Lote::find($request->lote_id);
            $producto = Producto::find($request->orden_productos);

            $last_caja_number = $lote->getLastCajaNumber();

            $info_caja = array(
                'caja_number' => ($last_caja_number+1),
                'caja_orden_p_id' => $request->orden_id,
                'caja_producto_id' => $producto->producto_id,
                'caja_peso_real' => $request->peso_real,
                'caja_peso_bruto' => $request->peso_bruto,
                'caja_unidades' => $request->unidades,
                'caja_estado' => 'OK'
            );

            $caja = Caja::create($info_caja);

            $number = str_pad($caja->caja_number, 6, 0, STR_PAD_LEFT);

            $barcode = 'AF0'.$request->etiqueta_year.'0'.
                        $lote->lote_number.'0'.
                        $number;

            $info_etiqueta = array(
                'etiqueta_caja_id' => $caja->caja_id,
                'etiqueta_barcode' => $barcode,
                'etiqueta_fecha'   => \Carbon\Carbon::createFromFormat('d-m-Y', $request->etiqueta_fecha)
            );

            $etiqueta = Etiqueta::create($info_etiqueta);

            /*$data['fecha_produccion'] = \Carbon\Carbon::now()->format('d-m-Y');
            $data['fecha_vencimiento'] = \Carbon\Carbon::createFromFormat('Y-m-d', $lote->lote_fecha_expiracion)->format('d-m-Y');
            $data['especie'] = \Config::get('options.especie')[$lote->lote_especie_id];
            $data['producto'] = $producto->producto_nombre;
            $data['calibre'] = \Config::get('options.calibre')[$producto->producto_calibre_id];
            $data['calidad'] = \Config::get('options.calidad')[$producto->producto_calidad_id];
            $data['piezas'] = $request->unidades;
            $data['peso_neto'] = $request->peso_bruto;
            $data['caja_number'] = $number;
            $data['barcode'] = \DNS1D::getBarcodePNG($barcode, "C128");
            $data['lote_number'] = $lote->lote_number;

            $view =  \View::make('admin.etiqueta.invoice', 
                        compact('data'))->render();

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view);*/
            $resp = ["estado" => "ok",
                    "etiqueta_id" => $etiqueta->etiqueta_id];

        }
        catch ( Exception $e ){
            \DB::rollback();
            $resp = ["estado" => "nok"];
        }

        \DB::commit();

        //return $pdf->stream('invoice', array ("Attachment" => false));
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

                $caja = $etiqueta->caja;
                $caja->caja_posicion()->attach($request->select_posicion);

                $resp = ["ok"];
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
            $etiqueta = Etiqueta::where('etiqueta_barcode', $request->etiqueta_barcode)->first();

            if($etiqueta->etiqueta_estado == 'ANULADA')
            {
                return response()->json(["nok","Esta etiqueta ya fue anulada."]);
            }

            \DB::beginTransaction();

            try{
                $etiqueta->etiqueta_estado = 'ANULADA';
                //se guardan los cambios en la base de datos
                $etiqueta->save();

                $etiqueta->delete();

                $caja = $etiqueta->caja;

                $caja_posicion = CajaPosicion::where('caja_posicion_caja_id', $caja->caja_id)
                                        ->first()
                                        ->delete();

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
