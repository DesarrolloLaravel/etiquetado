<?php

namespace App\Http\Controllers\Geren;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Http\Requests\Trim\CreateRequest;
use App\Http\Requests\Trim\UpdateRequest;

use App\Models\Producto;
use App\Models\Trim;

class TrimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $trims = Trim::all();

        //si es petición ajax
        if($request -> ajax()){

            //si no hay variantes
            if($trims-> count() == 0){
                return '{"data":[]}';
            }

            //si hay variantes
            $dt_json = '{ "data": [';

            //guarda en json los datos de todos los variantes
            foreach ($trims as $trim) {
                $dt_json .= '["'.$trim->trim_id.'","'
                                .$trim->trim_name.'"],';
            }

            //elimina la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);

            //cierra el json
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('geren.trim.index', compact('trims'));    
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
                'trim_name' => 'unique:trim,trim_name'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json(["nok"]);
            }            
            Trim::create($request->all());
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

            $prole = Producto::where('producto_trim_id',$request->trim_id)->count();
            
            if($prole == 0){
                //se busca la variante a modificar
                $trim = Trim::findOrFail($request->trim_id);

                return response()->json($trim);        
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
            $trims = Trim::findOrFail($request->trim_id);

            //genera un array con la info del update
            $info = array(
                'trim_name' => $request->trim_name,
            );

            //añade la info al elaborador encontrado
            $trims->fill($info);

            //guarda en la bd el elaborador ya modificado
            $trims->save();

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
                'trim_id' => 'required|exists:trim,trim_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco el vairnate a eliminar
            $trim = Trim::findOrFail($request->trim_id);
            //elimino la variante
            $trim->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
