<?php

namespace App\Http\Controllers\Almacenamiento;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Empresa\CreateRequest;
use App\Http\Requests\Empresa\UpdateRequest;

use App\Models\Empresa;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $empresas = Empresa::where('empresa_enable', 1)->get();

        if($request->ajax())
        {
            if($empresas->count() == 0)
            {
                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($empresas as $empresa) {
                //completo el json
                $dt_json .= '["'.$empresa->empresa_id.'","'
                                .$empresa->empresa_name.'","'
                                .$empresa->empresa_rut.'"],';
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
            return view('almacenamiento.empresa.index', compact('empresas'));
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
            Empresa::create($request->all());
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
            $procesador = Empresa::findOrFail($request->empresa_id);
            
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
            $empresa = Empresa::findOrFail($request->empresa_id);

            //se crea un array con la información enviada desde el cliente
            $info = array(
                'empresa_name' => $request->empresa_name, 
                'empresa_rut' => $request->empresa_rut);

            //se pasa la información a la compañia encontrada
            $empresa->fill($info);
            //se guardan los cambios en la base de datos
            $empresa->save();
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
                'empresa_id' => 'required|exists:empresa,empresa_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la compañia a eliminar
            $empresa = Empresa::findOrFail($request->empresa_id);
            //elimino la compañia
            $empresa->disable();

            $empresa->save();
            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
