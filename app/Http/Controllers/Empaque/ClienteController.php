<?php

namespace App\Http\Controllers\Empaque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Cliente\CreateRequest;
use App\Http\Requests\Cliente\UpdateRequest;

use App\Models\Cliente;
use App\Models\OrdenProduccion;
use App\Models\OrdenDespacho;


class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $clientes = Cliente::all();

        //si es petición ajax
        if($request -> ajax()){

            //si no hay cliente
            if($clientes-> count() == 0){
                return '{"data":[]}';
            }

            //si no hay cliente
            $dt_json = '{ "data": [';

            //guarda en json los datos de todos los clientes
            foreach ($clientes as $cliente) {
                $dt_json .= '["'.$cliente->cliente_id.'","'
                                .$cliente->cliente_nombre.'"],';
            }

            //elimina la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);

            //cierra el json
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('empaque.cliente.index', compact('clientes'));    
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
            //crea un cliente con los datos del request

            $v = \Validator::make($request->all(), [
                'cliente_nombre' => 'unique:cliente,cliente_nombre'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json(["nok"]);
            }            
            Cliente::create($request->all());
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

            $desp = OrdenDespacho::where('orden_cliente_id',$request->cliente_id)->count();
            $prod = OrdenProduccion::where('orden_cliente_id',$request->cliente_id)->count();
            
            if($desp == 0 && $prod == 0){
                //se busca el cliente a modificar
                $cliente = Cliente::findOrFail($request->cliente_id);

                return response()->json($cliente);        
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

            //busca por el id al elaborador
            $cliente = Cliente::findOrFail($request->cliente_id);

            //genera un array con la info del update
            $info = array(
                'cliente_nombre' => $request->cliente_nombre
            );

            //añade la info al elaborador encontrado
            $cliente->fill($info);

            //guarda en la bd el elaborador ya modificado
            $cliente->save();

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
                'cliente_id' => 'required|exists:cliente,cliente_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la elaboradora a eliminar
            $cliente = Cliente::findOrFail($request->cliente_id);
            //elimino la compañia
            $cliente->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
