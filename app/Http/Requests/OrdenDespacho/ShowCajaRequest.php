<?php

namespace App\Http\Requests\OrdenDespacho;

use Illuminate\Validation\Factory as ValidationFactory;

use App\Http\Requests\Request;

use App\Models\OrdenDespacho;
use App\Models\Etiqueta;

class ShowCajaRequest extends Request
{
    public function __construct(ValidationFactory $validationFactory)
    {
        //defino nueva propiedad para validación
        $validationFactory->extend(
            //defino el nombre de la nueva propiedad
            //esta nueva propiedad sirve para verificar
            //si efectivamente puedo asociar la caja a la orden se despacho
            //de acuerdo al detalle de esta
            'canAddCajaOrden',
            function ($attribute, $value, $parameters) {
                $orden = OrdenDespacho::with('productos')->findOrFail($parameters[0]);
                //tengo los distintos lotes asociados a esa orden de despacho
                $lotes = $orden->lotes->unique('lote_id')->values()->all();

                //obtengo la caja a despachar
                $etiqueta = Etiqueta::where('etiqueta_barcode', $value)
                            ->first();

                if(is_null($etiqueta))
                    return false;
                else
                    $caja = $etiqueta->caja;

                $in_despacho = false;
                //para cada lote se buscara si la caja a despachar pertenece a ese lote
                foreach ($lotes as $lote) {
                    //obtengo los productos que se han generado de ese lote
                    $productos = $orden->productos;

                    foreach ($productos as $producto) {
                        # code...
                        $cajas = $lote->getCajasByProduct($producto->producto_id);
                        if($cajas->contains($caja))
                        {
                            $in_despacho = true;
                            break;
                        }
                    }
                    
                }

                return $in_despacho;
            },
            'La Caja no puede ser agregada a esta Orden de Despacho debido a que no corresponde al detalle definido.'
        );

        //defino nueva propiedad para validación
        $validationFactory->extend(
            //defino el nombre de la nueva propiedad
            'uniqueCajaOrden',
            function ($attribute, $value, $parameters) {
                $orden = OrdenDespacho::findOrFail($parameters[0]);

                $etiqueta = Etiqueta::where('etiqueta_barcode', $value)
                            ->first();

                if(is_null($etiqueta))
                    return false;
                else
                    $caja = $etiqueta->caja;
                
                $detalle_orden = $orden->despacho_lote()->with('cajas');

                $exists = true;
                foreach ($detalle_orden as $detalle) {
                    # code...
                    $cajas = $detalle->cajas;

                    if($cajas->contains($caja))
                    {
                        $exists = false;
                        break;
                    }
                }

                return $exists;
            },
            'La Caja no puede ser agregada debido a que se encuentra asociada a otra Orden de despacho.'
        );

    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'etiqueta_barcode' => 'required|exists:etiqueta,etiqueta_barcode|uniqueCajaOrden:'.$this->request->get('orden_id').'|canAddCajaOrden:'.$this->request->get('orden_id'),
            'orden_id' => 'required|exists:orden_despacho,orden_id'
        ];
    }

    public function messages()
    {
        return [
            'etiqueta_barcode.required' => 'El Código es obligatorio',
            'etiqueta_barcode.exists' => 'El Código ingresado no existe. Verifica tu información.',
            'orden_id.required' => 'Debes seleccionar una Orden de Despacho',
            'orden_id.exists' => 'Ha ocurrido un error. Inténtalo más tarde.'
        ];
    }
}
