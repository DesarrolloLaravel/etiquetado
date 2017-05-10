<?php

namespace App\Http\Controllers\Admin;

use App\Models\Calidad;
use App\Models\Producto;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Calidad\CreateRequest;
use App\Http\Requests\Calidad\UpdateRequest;

class CalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $calidades = Calidad::all();

        if($request->ajax())
        {
            if($calidades->count() == 0)
            {
                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($calidades as $calidad) {
                //completo el json
                $dt_json .= '["'.$calidad->calidad_id.'","'
                    .$calidad->calidad_nombre.'"],';
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
            return view('admin.calidad.index', compact('calidades'));
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
            Calidad::create($request->all());
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
            $prod=Producto::where('producto_calidad_id',$request->calidad_id)->count();

           if($prod == 0){

                $calidad = Calidad::findOrFail($request->calidad_id);

                return response()->json($calidad);        
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
            $calidad = Calidad::findOrFail($request->calidad_id);

            //se crea un array con la información enviada desde el cliente
            $info = array(
                'calidad_nombre' => $request->calidad_nombre);

            //se pasa la información a la compañia encontrada
            $calidad->fill($info);
            //se guardan los cambios en la base de datos
            $calidad->save();
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
                'calidad_id' => 'required|exists:calidad,calidad_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la compañia a eliminar
            $calidad = Calidad::findOrFail($request->calidad_id);
            //elimino la compañia
            $calidad->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
