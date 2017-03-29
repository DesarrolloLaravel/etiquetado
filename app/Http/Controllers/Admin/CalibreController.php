<?php

namespace App\Http\Controllers\Admin;

use App\Models\Calibre;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Calibre\CreateRequest;
use App\Http\Requests\Calibre\UpdateRequest;

class CalibreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $calibres = Calibre::all();
        $unidades = ['null' => 'Ninguno'] + UnidadMedida::orderBy('unidad_medida_nombre', 'ASC')
                ->get()
                ->lists('unidad_medida_nombre', 'unidad_medida_id')->all();

        if($request->ajax())
        {
            if($calibres->count() == 0)
            {
                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($calibres as $calibre) {
                //completo el json
                $dt_json .= '["'.$calibre->calibre_id.'","'
                    .$calibre->calibre_nombre.'"],';
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
            return view('admin.calibre.index', compact('calibres','unidades'));
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
            Calibre::create($request->all());
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
            $calibre = Calibre::findOrFail($request->calibre_id);
            $unidades = [''=>'Ninguno'] + 
                        UnidadMedida::orderBy('unidad_medida_nombre', 'ASC')
                        ->get()
                        ->lists('unidad_medida_nombre','unidad_medida_id')
                        ->all();
            
            $view = \View::make('admin.calibre.fields')
                    ->with('unidades', $unidades);

             $sections = $view->renderSections();
           

            return response()->json(["calibre" => $calibre,"section" => $sections['contentPanelCalibre']]);
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
            $calibre = Calibre::findOrFail($request->calibre_id);

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
            $calibre = Calibre::findOrFail($request->calibre_id);
            //elimino la compañia
            $calibre->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
