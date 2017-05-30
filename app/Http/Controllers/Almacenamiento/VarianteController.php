<?php

namespace App\Http\Controllers\Almacenamiento;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Http\Requests\Variante\CreateRequest;
use App\Http\Requests\Variante\UpdateRequest;

use App\Models\Producto;
use App\Models\Variante;

class VarianteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //toma todos las variantes
        $variantes = Variante::where('variante_id','>','0')->get();

        //si es petición ajax
        if($request -> ajax()){

            //si no hay variantes
            if($variantes-> count() == 0){
                return '{"data":[]}';
            }

            //si hay variantes
            $dt_json = '{ "data": [';

            //guarda en json los datos de todos los variantes
            foreach ($variantes as $variante) {
                $dt_json .= '["'.$variante->variante_id.'","'
                                .$variante->variante_name.'"],';
            }

            //elimina la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);

            //cierra el json
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('almacenamiento.variante.index', compact('variantes'));    
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
                'variante_name' => 'unique:variante,variante_name'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json(["nok"]);
            }            
            Variante::create($request->all());
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

            $prole = Producto::where('producto_variante_id',$request->variante_id)->count();
            
            if($prole == 0){
                //se busca la variante a modificar
                $variante = Variante::findOrFail($request->variante_id);

                return response()->json($variante);        
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
            $variante = Variante::findOrFail($request->variante_id);

            //genera un array con la info del update
            $info = array(
                'variante_name' => $request->variante_name,
            );

            //añade la info al elaborador encontrado
            $variante->fill($info);

            //guarda en la bd el elaborador ya modificado
            $variante->save();

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
                'variante_id' => 'required|exists:variante,variante_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco el vairnate a eliminar
            $variante = Variante::findOrFail($request->variante_id);
            //elimino la variante
            $variante->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
