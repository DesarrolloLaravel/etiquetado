<?php

namespace App\Http\Requests\OrdenDespacho;

use Illuminate\Validation\Factory as ValidationFactory;

use App\Http\Requests\Request;

use App\Models\OrdenDespacho;

class DiscountRequest extends Request
{
    public function __construct(ValidationFactory $validationFactory)
    {
        //defino nueva propiedad para validación
        $validationFactory->extend(
            //defino el nombre de la nueva propiedad
            //esta nueva propiedad sirve para verificar
            //si efectivamente puedo asociar la caja a la orden se despacho
            //de acuerdo al detalle de esta
            'hasCajas',
            function ($attribute, $value, $parameters) {
                $orden = OrdenDespacho::findOrFail($value);

                $detalle_orden = $orden->despacho_lote()->with('cajas')->get();

                $has_cajas = false;
                foreach ($detalle_orden as $detalle) {
                    # code...
                    if($detalle->cajas()->count() > 0)
                        $has_cajas = true;
                }

                return $has_cajas;
            },
            'No se puede realizar despacho debido a que no hay Cajas asociadas.'
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
            'orden_id' => 'required|exists:orden_despacho,orden_id|hasCajas'
        ];
    }

    public function messages()
    {
        return [
            'orden_id.required' => 'Debes seleccionar una Orden de Despacho',
            'orden_id.exists' => 'Ha ocurrido un error. Inténtalo más tarde.'
        ];
    }
}
