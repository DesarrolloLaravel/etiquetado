<?php

namespace App\Http\Controllers\Empaque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lote;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $lotes = Lote::all();

        if($request->ajax())
        {
            if($request->q == "etiqueta")
            {
                $lotes = Lote::with('orden_produccion')->get()
                        ->where('lote_produccion', 'SI');
                        
                if($lotes->count() == 0)
                {
                    return '{"data":[]}';
                }
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($lotes as $lote) {
                //completo el json
                $dt_json .= '["","'
                                .$lote->lote_id.'","'
                                .\Config::get('options.lote_tipo')[$lote->lote_tipo_id].'","'
                                .$lote->lote_n_documento.'","'
                                .$lote->procesador->procesador_name.'","'
                                .$lote->productor->productor_name.'","'
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
        else
        {
            return view('empaque.lote.index', compact('lotes'));
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
    public function show(Request $request)
    {
        //
        $lote = Lote::findOrFail($request->lote_id);

        if($request->ajax())
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

            $resp['caja_number'] = $lote->getLastCajaNumber()+1;
            
            return $resp;
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
