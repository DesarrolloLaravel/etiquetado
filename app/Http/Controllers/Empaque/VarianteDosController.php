<?php

namespace App\Http\Controllers\empaque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\VarianteDos\CreateRequest;
use App\Http\Requests\VarianteDos\UpdateRequest;

use App\Models\Producto;
use App\Models\VarianteDos;

class VarianteDosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
         $variantes = VarianteDos::where('varianteDos_id','>','0')->get();

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
                $dt_json .= '["'.$variante->varianteDos_id.'","'
                                .$variante->varianteDos_name.'"],';
            }

            //elimina la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);

            //cierra el json
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('empaque.varianteDos.index', compact('variantes'));    
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
                'varianteDos_name' => 'unique:varianteDos,varianteDos_name'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json(["nok"]);
            }            
            VarianteDos::create($request->all());
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

            $prole = Producto::where('producto_v2_id',$request->varianteDos_id)->count();
            
            if($prole == 0){
                //se busca la variante a modificar
                $variante = VarianteDos::findOrFail($request->varianteDos_id);

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
            $variante = VarianteDos::findOrFail($request->varianteDos_id);

            //genera un array con la info del update
            $info = array(
                'varianteDos_name' => $request->varianteDos_name,
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
                'varianteDos_id' => 'required|exists:varianteDos,varianteDos_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco el vairnate a eliminar
            $variante = VarianteDos::findOrFail($request->varianteDos_id);
            //elimino la variante
            $variante->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
    
    
}
