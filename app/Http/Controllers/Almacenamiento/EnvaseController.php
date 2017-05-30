<?php

namespace App\Http\Controllers\Almacenamiento;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Envase\CreateRequest;
use App\Http\Requests\Envase\UpdateRequest;

use App\Models\Producto;
use App\Models\Envase;

class EnvaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $envases = Envase::where('envase_id','>','0')->get();

        //si es petición ajax
        if($request -> ajax()){

            //si no hay envases
            if($envases-> count() == 0){
                return '{"data":[]}';
            }

            //si no hay envases
            $dt_json = '{ "data": [';

            //guarda en json los datos de todos los envases
            foreach ($envases as $envase) {
                $dt_json .= '["'.$envase->envase_id.'","'
                                .$envase->envase_nombre.'","'
                                .$envase->envase_capacidad.'"],';
            }

            //elimina la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);

            //cierra el json
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('almacenamiento.envase.index', compact('envases'));    
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateRequest $request)
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
                'envase_nombre' => 'unique:envase,envase_nombre'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json(["nok"]);
            }            
            Envase::create($request->all());
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

            $prole = Producto::where('producto_envase1_id',$request->envase_id)->count();
            
            if($prole == 0){
                //se busca el elaborador a modificar
                $envase = Envase::findOrFail($request->envase_id);

                return response()->json($envase);        
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

            //busca por el id al envase
            $envase = Envase::findOrFail($request->envase_id);

            //genera un array con la info del update
            $info = array(
                'envase_capacidad' => $request->envase_capacidad,
                'envase_nombre' => $request->envase_nombre
            );

            //añade la info al elaborador encontrado
            $envase->fill($info);

            //guarda en la bd el elaborador ya modificado
            $envase->save();

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
                'envase_id' => 'required|exists:envase,envase_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco el envase a eliminar
            $envase = Envase::findOrFail($request->envase_id);
            //elimino la compañia
            $envase->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
