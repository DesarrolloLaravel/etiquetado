<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Formato\CreateRequest;
use App\Http\Requests\Formato\UpdateRequest;

use App\Models\Formato;

class FormatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $formatos = Formato::all();

        if($request -> ajax()){

            if($formatos-> count() == 0){
                return '{"data":[]}';
            }
            $dt_json = '{ "data": [';

            foreach ($formatos as $formato) {
                $dt_json .= '["'.$formato->formato_id.'","'
                                .$formato->formato_nombre.'","'
                                .$formato->formato_abreviatura.'"],';
            }

            $dt_json = substr($dt_json, 0, -1);
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('admin.formato.index', compact('formato'));    
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
         //si la peticion es ajax
        if($request->ajax())
        {
            //creo y guardo una compañia con toda la informacion enviada
            Formato::create($request->all());
            //envio respuesta al cliente
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
        //si la peticion es ajax
        if($request->ajax())
        {  
            //busco la compañia a consultar
            $formato = Formato::findOrFail($request->formato_id);
            
            return response()->json($formato);
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
        if($request->ajax())
        {
            $formato = Formato::findOrFail($request->formato_id);

            //se crea un array con la información enviada desde el cliente
            $info = array(
                'formato_nombre' => $request->formato_nombre, 
                'formato_abreviatura' => $request->formato_abreviatura);

            //se pasa la información a la compañia encontrada
            $formato->fill($info);
            //se guardan los cambios en la base de datos
            $formato->save();
            //se envia respuesta al cliente
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
                'formato_id' => 'required|exists:formato,formato_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la compañia a eliminar
            $formato = Formato::findOrFail($request->formato_id);
            //elimino la compañia
            $formato->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }}
