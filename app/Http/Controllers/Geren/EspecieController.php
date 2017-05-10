<?php

namespace App\Http\Controllers\Admin;

use App\Models\Especie;
use App\Models\Producto;
use App\Models\Lote;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Especie\CreateRequest;
use App\Http\Requests\Especie\UpdateRequest;

class EspecieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $especies = Especie::all();

        if($request->ajax())
        {
            if($especies->count() == 0)
            {
                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($especies as $especie) {
                //completo el json
                $dt_json .= '["'.$especie->especie_id.'","'
                    .$especie->especie_name.'","'
                    .$especie->especie_comercial_name.'","'
                    .$especie->especie_abbreviation.'"],';
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
            return view('admin.especie.index', compact('especies'));
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
            Especie::create($request->all());
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
            $lot = Lote::where('lote_especie_id',$request->especie_id)->count();
            $prod = Producto::where('producto_especie_id',$request->especie_id)->count();

            if($lot == 0 && $prod==0) {
                //se busca el elaborador a modificar
                $especie = Especie::findOrFail($request->especie_id);

                return response()->json($especie);        
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
        if($request->ajax())
        {
            $especie = Especie::findOrFail($request->especie_id);

            //se crea un array con la información enviada desde el cliente
            $info = array(
                'especie_comercial_name' => $request->especie_comercial_name,
                'especie_name' => $request->especie_name,
                'especie_abbreviation' => $request->especie_abbreviation);

            //se pasa la información a la compañia encontrada
            $especie->fill($info);
            //se guardan los cambios en la base de datos
            $especie->save();
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
        //
        if($request->ajax())
        {
            //se crea el validador con la información enviada desde el cliente
            //y con las reglas de validación respectiva
            $v = \Validator::make($request->all(), [
                'especie_id' => 'required|exists:especie,especie_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la compañia a eliminar
            $especie = Especie::findOrFail($request->especie_id);
            //elimino la compañia
            $especie->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
