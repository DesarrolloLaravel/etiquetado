<?php

namespace App\Http\Controllers\Empaque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Condicion\CreateRequest;
use App\Http\Requests\Condicion\UpdateRequest;

use App\Models\Producto;
use App\Models\Condicion;

class CondicionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //toma todos las condiciones
        $condiciones = Condicion::where('condicion_id','>','0')->get();

        //si es petición ajax
        if($request -> ajax()){

            //si no hay variantes
            if($condiciones-> count() == 0){
                return '{"data":[]}';
            }

            //si hay variantes
            $dt_json = '{ "data": [';

            //guarda en json los datos de todos los variantes
            foreach ($condiciones as $condicion) {
                $dt_json .= '["'.$condicion->condicion_id.'","'
                                .$condicion->condicion_name.'"],';
            }

            //elimina la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);

            //cierra el json
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('empaque.condicion.index', compact('condiciones'));    
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
        //
        if($request -> ajax()){
            //crea un elaborador con los datos del request

            $v = \Validator::make($request->all(), [
                'condicion_name' => 'unique:condicion,condicion_name'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json(["nok"]);
            }            
            Condicion::create($request->all());
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
        //
        if($request->ajax()){

            $prole = Producto::where('producto_condicion_id',$request->condicion_id)->count();
            
            if($prole == 0){
                //se busca la variante a modificar
                $condicion = Condicion::findOrFail($request->condicion_id);

                return response()->json($condicion);        
            }
            else{
                return response()->json(["nok"]);
            }
    
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

            //busca por el id al variante
            $condicion = Condicion::findOrFail($request->condicion_id);

            //genera un array con la info del update
            $info = array(
                'condicion0_name' => $request->condicion_name,
            );

            //añade la info al elaborador encontrado
            $condicion->fill($info);

            //guarda en la bd el elaborador ya modificado
            $condicion->save();

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
        //
        if($request->ajax())
        {
            //se crea el validador con la información enviada desde el cliente
            //y con las reglas de validación respectiva
            $v = \Validator::make($request->all(), [
                'condicion_id' => 'required|exists:condicion,condicion_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco el vairnate a eliminar
            $condicion = Condicion::findOrFail($request->condicion_id);
            //elimino la variante
            $condicion->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
