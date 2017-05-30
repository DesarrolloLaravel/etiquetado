<?php

namespace App\Http\Controllers\Almacenamiento;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Procesador\CreateRequest;
use App\Http\Requests\Procesador\UpdateRequest;

use App\Models\Procesador;

class ProcesadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $procesadores = Procesador::all();

        if($request->ajax())
        {
            if($procesadores->count() == 0)
            {
                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($procesadores as $procesador) {
                //completo el json
                $dt_json .= '["'.$procesador->procesador_id.'","'
                                .$procesador->procesador_name.'","'
                                .$procesador->procesador_rut.'"],';
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
            return view('almacenamiento.procesador.index', compact('procesadores'));
        }
        
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
            Procesador::create($request->all());
            //envio respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
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
            $procesador = Procesador::findOrFail($request->procesador_id);
            
            return response()->json($procesador);
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
            $procesador = Procesador::findOrFail($request->procesador_id);

            //se crea un array con la información enviada desde el cliente
            $info = array(
                'procesador_name' => $request->procesador_name, 
                'procesador_rut' => $request->procesador_rut);

            //se pasa la información a la compañia encontrada
            $procesador->fill($info);
            //se guardan los cambios en la base de datos
            $procesador->save();
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
                'procesador_id' => 'required|exists:procesador,procesador_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la compañia a eliminar
            $procesador = Procesador::findOrFail($request->procesador_id);
            //elimino la compañia
            $procesador->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
