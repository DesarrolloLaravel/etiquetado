<?php

namespace App\Http\Requests\OrdenTrabajo;

use App\Http\Requests\Request;

class CreateRequest extends Request
{
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
            'orden_trabajo_orden_produccion'    => 'required|exists:orden_produccion,orden_id',
            'orden_trabajo_especie'     => 'required',
            'orden_fecha'           => 'required|date_format:d-m-Y',
            'orden_trabajo_producto'    => 'required',
            'etiquetas'             => 'required'
        ];
    }

    public function messages()
    {
        return [
            //
            'orden_trabajo_orden_produccion.required'   => 'Debes seleccionar una Orden de Producción',
            'orden_trabajo_especie.required'        => 'La Especie es obligatoria',
            'orden_fecha.required'              => 'Debes seleccionar una Fecha para la orden',
            'orden_fecha.date_format'           => 'La Fecha de orden tiene un formato incorrecto',
            'etiquetas.required'                => 'Debes seleccionar Productos'
        ];
    }
}
