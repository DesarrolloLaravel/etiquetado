<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Elaborador;

class ElaboradorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //toma todos los elaboradores
        $elaboradores = Elaborador::all();

        //si es petición ajax
        if($request -> ajax()){

            //si no hay elaboradores
            if($elaboradores-> count() == 0){
                return '{"data":[]}';
            }

            //si no hay elaboradores
            $dt_json = '{ "data": [';

            //guarda en json los datos de todos los elaboradores
            foreach ($elaboradores as $elaborador) {
                $dt_json .= '["'.$elaborador->elaborador_id.'","'
                                .$elaborador->elaborador_name.'","'
                                .$elaborador->elaborador_rut.'"],';
            }

            //elimina la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);

            //cierra el json
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('admin.elaborador.index', compact('elaboradores'));    
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
    public function store(CreateRequest $request)
    {
        //si es petición ajax
        if($request -> ajax()){
            //crea un elaborador con los datos del request
            Elaborador::create($request->all());
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
