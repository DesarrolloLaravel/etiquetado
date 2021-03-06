<?php

namespace App\Http\Controllers\Empaque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Productor\CreateRequest;
use App\Http\Requests\Productor\UpdateRequest;

use App\Models\Productor;
use App\Models\Lote;

class ProductorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //toma todos los productores
        $productores = Productor::all();

        //si es petición ajax
        if($request -> ajax()){

            //si no hay productores
            if($productores-> count() == 0){
                return '{"data":[]}';
            }

            //si no hay productores
            $dt_json = '{ "data": [';

            //guarda en json los datos de todos los productores
            foreach ($productores as $productor) {
                $dt_json .= '["'.$productor->productor_id.'","'
                                .$productor->productor_name.'"],';
            }

            //elimina la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);

            //cierra el json
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('empaque.productor.index', compact('productores'));    
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
        //si es petición ajax
        if($request -> ajax()){
            //crea un elaborador con los datos del request

            $v = \Validator::make($request->all(), [
                'productor_name' => 'unique:productor,productor_name'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json(["nok"]);
            }            
            Productor::create($request->all());
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

            
            $lot = Lote::where('lote_productor_id',$request->productor_id)->count();
            
            if($lot == 0){
                //se busca el elaborador a modificar
                $productor = Productor::findOrFail($request->productor_id);

                return response()->json($productor);        
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
        if($request-> ajax()){

            //busca por el id al elaborador
            $productor = Productor::findOrFail($request->productor_id);

            //genera un array con la info del update
            $info = array(
                'productor_name' => $request->productor_name
            );

            //añade la info al elaborador encontrado
            $productor->fill($info);

            //guarda en la bd el elaborador ya modificado
            $productor->save();

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
    public function delete (Request $request)
    {
        //si la petición es ajax
        if($request->ajax())
        {
            //se crea el validador con la información enviada desde el cliente
            //y con las reglas de validación respectiva
            $v = \Validator::make($request->all(), [
                'productor_id' => 'required|exists:productor,productor_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la elaboradora a eliminar
            $productor = Productor::findOrFail($request->productor_id);
            //elimino la compañia
            $productor->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
