<?php

namespace App\Http\Controllers\Admin;

use App\Models\Calibre;
use App\Models\Calidad;
use App\Models\Condicion;
use App\Models\Envase;
use App\Models\Envase_Dos;
use App\Models\Formato;
use App\Models\Trim;
use App\Models\Variante;
use App\Models\VarianteDos;
use App\Models\Producto;
use App\Models\Especie;
use Log;




use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Producto\CreateRequest;
use App\Http\Requests\Producto\UpdateRequest;


class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $productos = Producto::all();

        if($request->ajax())
        {
            if($productos->count() == 0)
            {
                return '{"data":[]}';
            }
            //inicializo el json
            $dt_json = '{ "data" : [';

            //para cada compañia
            foreach ($productos as $producto) {
                //completo el json
                $dt_json .= '["'.$producto->producto_id.'","'
                                .$producto->getFullName().'","'
                                .$producto->producto_peso.'"],';
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
            $especies = [''=>'NO APLICA'] + Especie::orderBy('especie_abbreviation', 'ASC')
                    ->get()
                    ->lists('especie_abbreviation','especie_id')->all();

            $condiciones = [''=>'NO APLICA'] + Condicion::orderBy('condicion_name', 'ASC')
                    ->get()
                    ->lists('condicion_name','condicion_id')->all();

            $formatos = [''=>'NO APLICA'] + Formato::orderBy('formato_nombre', 'ASC')
                    ->get()
                    ->lists('formato_abreviatura','formato_id')->all();

            $trims = [''=>'NO APLICA'] + Trim::orderBy('trim_name', 'ASC')
                    ->get()
                    ->lists('trim_name','trim_id')->all();

            $calidades = ['' => 'NO APLICA'] + Calidad::orderBy('calidad_nombre', 'ASC')
                    ->get()
                    ->lists('calidad_nombre', 'calidad_id')->all();

            $variantes = [''=>'NO APLICA'] + Variante::orderBy('variante_name', 'ASC')
                    ->get()
                    ->lists('variante_name','variante_id')->all();

            $variantes_dos = [''=>'NO APLICA'] + VarianteDos::orderBy('varianteDos_name', 'ASC')
                    ->get()
                    ->lists('varianteDos_name','varianteDos_id')->all();

            $calibres = ['' => 'NO APLICA'] + Calibre::orderBy('calibre_nombre', 'ASC')
                    ->get()
                    ->lists('calibre_nombre', 'calibre_id')->all();

            $envases = ['' => 'NO APLICA'] + Envase::orderBy('envase_nombre', 'ASC')
                    ->get()
                    ->lists('envase_nombre', 'envase_id')->all();

            $envases_dos = ['' => 'NO APLICA'] + Envase_Dos::orderBy('envaseDos_nombre', 'ASC')
                    ->get()
                    ->lists('envaseDos_nombre', 'envaseDos_id')->all();

            return view('admin.producto.index', compact('productos', 'especies','condiciones', 'formatos', 'trims',
                'calidades','variantes','variantes_dos', 'calibres', 'envases','envases_dos'));
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
            $producto = new Producto();
            $producto->producto_nombre = $request->producto_nombre;
            $producto->producto_codigo = $request->producto_codigo;
            $producto->producto_peso = $request->producto_peso;

            $producto->producto_condicion_id = $request->producto_condicion;
            $producto->producto_variante_id = $request->producto_variante;
            $producto->producto_v2_id = $request->producto_v2;
            $producto->producto_envase2_id = $request->producto_envase2;

            $producto->producto_especie_id=$request->producto_especie;
            $producto->producto_formato_id=$request->producto_formato;
            $producto->producto_trim_id = $request->producto_trim;
            $producto->producto_calidad_id = $request->producto_calidad;
            $producto->producto_calibre_id = $request->producto_calibre;
            $producto->producto_envase1_id = $request->producto_envase1;
            $producto->producto_fullname=$producto->getFullName();

            $producto->save();

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
    public function show(Request $request)
    {
        //
        $producto = Producto::findOrFail($request->producto_id);

        if($request->ajax())
        {
            $resp = [];

            $resp['producto_id'] = $producto->producto_id;
            $resp['producto_descripcion'] = $producto->producto_descripcion;
            $resp['producto_peso'] = $producto->producto_peso;
            $resp['producto_fullname'] = $producto->getFullName();

            return $resp;
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
        //
        if($request->ajax())
        {
            $producto = Producto::findOrFail($request->producto_id);
            return response()->json(["producto" => $producto]);    
              
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
            $producto = Producto::findOrFail($request->producto_id);

            //se crea un array con la información enviada desde el cliente

            $producto->producto_nombre = $request->producto_nombre;
            $producto->producto_codigo = $request->producto_codigo;
            $producto->producto_peso = $request->producto_peso;

            $producto->producto_condicion_id = $request->producto_condicion;
            $producto->producto_variante_id = $request->producto_variante;
            $producto->producto_v2_id = $request->producto_v2;
            $producto->producto_envase2_id = $request->producto_envase2;

            $producto->producto_especie_id=$request->producto_especie;
            $producto->producto_formato_id=$request->producto_formato;
            $producto->producto_trim_id = $request->producto_trim;
            $producto->producto_calidad_id = $request->producto_calidad;
            $producto->producto_calibre_id = $request->producto_calibre;
            $producto->producto_envase1_id = $request->producto_envase1;
            $producto->producto_fullname=$producto->getFullName();

            //se pasa la información a la compañia encontrada
            //se guardan los cambios en la base de datos
            $producto->save();
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
        //
        if($request->ajax())
        {
            //se crea el validador con la información enviada desde el cliente
            //y con las reglas de validación respectiva
            $v = \Validator::make($request->all(), [
                'producto_id' => 'required|exists:producto,producto_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la compañia a eliminar
            $producto = Producto::findOrFail($request->producto_id);
            //elimino la compañia
            $producto->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
