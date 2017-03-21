<?php

namespace App\Http\Controllers\Admin;

use App\Models\UnidadMedida;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\UnidadMedida\CreateRequest;
use App\Http\Requests\UnidadMedida\UpdateRequest;

class UnidadMedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $unidades_medida = UnidadMedida::all();

        if($request->ajax())
        {
            if($unidades_medida->count() == 0)
            {

                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($unidades_medida as $unidad) {
                //completo el json
                $dt_json .= '["'.$unidad->unidades_medida_id.'","'
                    .$unidad->unidades_medida_nombre.'","'
                    .$unidad->unidades_medida_abreviacion'"],';
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
            return view('admin.unidades_medida.index', compact('unidades_medida'));
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
        if($request->ajax())
        {
            //creo y guardo una compañia con toda la informacion enviada
            UnidadMedida::create($request->all());
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
        //
        if($request->ajax())
        {
            $calibre = UnidadMedida::findOrFail($request->calibre_id);

            return response()->json($calibre);
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
            $calibre = UnidadMedida::findOrFail($request->calibre_id);

            //se crea un array con la información enviada desde el cliente
            $info = array(
                'calibre_nombre' => $request->calibre_nombre);

            //se pasa la información a la compañia encontrada
            $calibre->fill($info);
            //se guardan los cambios en la base de datos
            $calibre->save();
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
    public function destroy(Request $request)
    {
        //si la petición es ajax
        if($request->ajax())
        {
            //se crea el validador con la información enviada desde el cliente
            //y con las reglas de validación respectiva
            $v = \Validator::make($request->all(), [
                'calibre_id' => 'required|exists:calibre,calibre_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la compañia a eliminar
            $calibre = UnidadMedida::findOrFail($request->calibre_id);
            //elimino la compañia
            $calibre->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
