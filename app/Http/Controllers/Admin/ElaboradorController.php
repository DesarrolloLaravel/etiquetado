<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Elaborador\CreateRequest;
use App\Http\Requests\Elaborador\UpdateRequest;

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
    public function edit(Request $request)
    {
        //revisa si es una petición ajax
        if($request->ajax()){

            //se busca el elaborador a modificar
            $elaborador = Elaborador::findOrFail($request->elaborador_id);

            return response()->json($elaborador);
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
        if($request-> ajax()){

            //busca por el id al elaborador
            $elaborador = Elaborador::findOrFail($request->elaborador_id);

            //genera un array con la info del update
            $info = array(
                'elaborador_name' => $request->elaborador_name,
                'elaborador_rut' => $request->elaborador_rut
            );

            //añade la info al elaborador encontrado
            $elaborador->fill($info);

            //guarda en la bd el elaborador ya modificado
            $elaborador->save();

            //retorna la respuesta
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
    public function delete(Request $request)
    {
       //si la petición es ajax
        if($request->ajax())
        {
            //se crea el validador con la información enviada desde el cliente
            //y con las reglas de validación respectiva
            $v = \Validator::make($request->all(), [
                'elaborador_id' => 'required|exists:elaborador,elaborador_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la elaboradora a eliminar
            $elaborador = Elaborador::findOrfail($request->elaborador);
            //elimino la compañia
            $elaborador->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
