<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Http\Request;

use App\Http\Requests\Nordic\CreateRequest;
use App\Http\Requests\Nordic\UpdateRequest;


use App\Http\Requests;
use App\Http\Controllers\Controller;

class NordicController extends Controller
{
    public function print_etiqueta($orden_id, $lote_id, $fecha)
    {
        $orden = OrdenTrabajo::findOrFail($orden_id);
        $lote = Lote::findOrFail($lote_id);
        $producto = Producto::findOrFail($orden->orden_trabajo_producto);

        $data['fecha_produccion'] = \Carbon\Carbon::createFromTimestamp($fecha)->format('d.m.Y');
        $data['fecha_vencimiento'] = \Carbon\Carbon::createFromFormat('Y-m-d', $lote->lote_fecha_expiracion)->format('d.m.Y');
        $data['trim'] = $producto->trim->trim_nombre;
        $data['calibre'] = $producto->calibre->calibre_nombre;
        $data['calidad'] = $producto->calidad->calidad_nombre;
        $data['v2'] = \Config::get('producto.v2')[$producto->producto_v2_id];
        $data['producto_id'] = $producto->producto_id;
        $data['orden_number'] = $orden->orden_id;
        $data['lote_number'] = $lote->lote_id;
        //dd($data);

        $view =  \View::make('admin.nordic.invoice',
            compact('data'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream('invoice', array ("Attachment" => false));

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        return view('admin.etiqueta.create', compact('lote_id', 'orden_id', 'producto_id',
            'producto_fullName', 'caja_id', 'productos', 'peso_estandar'));*/
        
        $proxima_caja = Caja::withTrashed()->max('caja_id') + 1;

        return view('admin.nordic.create',compact('proxima_caja'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        \DB::beginTransaction();

        try{
            $lote = Lote::findOrFail($request->lote_id);

            $orden = OrdenTrabajo::findOrFail($request->orden_id);

            $orden_producto_id = $orden->orden_trabajo_producto;
                

            $fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->etiqueta_fecha)->timestamp;

            $resp = ["estado" => "ok",
                "orden_id" => $orden->orden_trabajo_id,
                "lote_id" => $request->lote_id,
                "producto_id" => $orden_producto_id,
                "fecha" => $fecha];

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
