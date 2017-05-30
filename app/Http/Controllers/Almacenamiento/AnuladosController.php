<?php

namespace App\Http\Controllers\Almacenamiento;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Procesador;
use App\Models\Productor;
use App\Models\Lote;


class AnuladosController extends Controller
{
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
            
            
            $anulados = Lote::onlyTrashed()->with('procesador','productor')->get();
            

            if($anulados->count() == 0)
            {
                return '{"data":[]}'; 
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($anulados as $anulado) {
                //completo el json
                $dt_json .= '["","'
                                .$anulado->lote_id.'","'
                                .$anulado->lote_n_documento.'","'
                                .$anulado->procesador->procesador_name.'","'
                                .$anulado->productor->productor_name.'","'
                                .$anulado->lote_djurada.'","'
                                .$anulado->lote_produccion.'"],';
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
            return view('almacenamiento.anulados.index');
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
