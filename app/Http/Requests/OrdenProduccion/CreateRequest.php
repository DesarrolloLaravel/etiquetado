<?php

namespace App\Http\Requests\OrdenProduccion;

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
            'orden_cliente_id'      => 'exists:cliente,cliente_id',
            'orden_descripcion'     => 'required',
            'orden_fecha'           => 'required|date_format:d-m-Y|before:orden_fecha_inicio',
            'orden_fecha_inicio'    => 'required|date_format:d-m-Y|before:orden_fecha_compromiso',
            'orden_fecha_compromiso'=> 'required|date_format:d-m-Y',
            'productos'             => 'required'
        ];
    }

    public function messages()
    {
        return [
            //
            'orden_cliente_id.required'         => 'Debes seleccionar un Cliente',
            'orden_descripcion.required'        => 'La Descripción es obligatoria',
            'orden_fecha.required'              => 'Debes seleccionar una Fecha para la orden',
            'orden_fecha.before'                => 'Fecha Orden debe ser anterior a Fecha Inicio',
            'orden_fecha_inicio.required'       => 'Debes seleccionar una Fecha de inicio',
            'orden_fecha_inicio.before'         => 'Fecha Inicio debe ser anterior a Fecha de Compromiso',
            'orden_fecha_compromiso.required'   => 'Debes seleccionar una Fecha de compromiso',
            'orden_fecha.date_format'           => 'La Fecha de orden tiene un formato incorrecto',
            'orden_fecha_inicio.date_format'    => 'La Fecha de inicio tiene un formato incorrecto',
            'orden_fecha_compromiso.date_format'=> 'La Fecha de compromiso tiene un formato incorrecto',
            'productos.required'                => 'Debes seleccionar Productos'
        ];
    }
}
