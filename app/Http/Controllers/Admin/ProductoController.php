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




use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Producto\CreateRequest;


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
            $especies = ['null'=>'NO APLICA'] + Especie::orderBy('especie_abbreviation', 'ASC')
                    ->get()
                    ->lists('especie_abbreviation','especie_id')->all();

            $condiciones = ['null'=>'NO APLICA'] + Condicion::orderBy('condicion_name', 'ASC')
                    ->get()
                    ->lists('condicion_name','condicion_id')->all();

            $formatos = ['null'=>'NO APLICA'] + Formato::orderBy('formato_nombre', 'ASC')
                    ->get()
                    ->lists('formato_abreviatura','formato_id')->all();

            $trims = ['null'=>'NO APLICA'] + Trim::orderBy('trim_name', 'ASC')
                    ->get()
                    ->lists('trim_name','trim_id')->all();

            $calidades = ['null' => 'NO APLICA'] + Calidad::orderBy('calidad_nombre', 'ASC')
                    ->get()
                    ->lists('calidad_nombre', 'calidad_id')->all();

            $variantes = ['null'=>'NO APLICA'] + Variante::orderBy('variante_name', 'ASC')
                    ->get()
                    ->lists('variante_name','variante_id')->all();

            $variantes_dos = ['null'=>'NO APLICA'] + VarianteDos::orderBy('varianteDos_name', 'ASC')
                    ->get()
                    ->lists('varianteDos_name','varianteDos_id')->all();

            $calibres = ['null' => 'NO APLICA'] + Calibre::orderBy('calibre_nombre', 'ASC')
                    ->get()
                    ->lists('calibre_nombre', 'calibre_id')->all();

            $envases = ['null' => 'NO APLICA'] + Envase::orderBy('envase_nombre', 'ASC')
                    ->get()
                    ->lists('envase_nombre', 'envase_id')->all();

            $envases_dos = ['null' => 'NO APLICA'] + Envase_Dos::orderBy('envaseDos_nombre', 'ASC')
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

            $especie = Especie::find($request->producto_especie);
            $producto->especie()->associate($especie);

            $formato = Formato::find($request->producto_formato);
            $producto->formato()->associate($formato);

            $trim = Trim::find($request->producto_trim);
            $producto->trim()->associate($trim);

            $calidad = Calidad::find($request->producto_calidad);
            $producto->calidad()->associate($calidad);

            $calibre = Calibre::find($request->producto_calibre);
            $producto->calibre()->associate($calibre);

            $envase = Envase::find($request->producto_envase1);
            $producto->envase()->associate($envase);

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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
