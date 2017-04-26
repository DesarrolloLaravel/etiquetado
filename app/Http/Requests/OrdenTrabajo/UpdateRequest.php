<?php

namespace App\Http\Requests\OrdenProduccion;

use App\Http\Requests\Request;

class UpdateRequest extends Request
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
            'orden_number'          => 'required|exists:orden_produccion,orden_id',
            'orden_lote_id'         => 'required|exists:lote,lote_id',
            'orden_cliente_id'      => 'exists:cliente,cliente_id',
            'orden_descripcion'     => 'required',
            'orden_fecha'           => 'required|date_format:d-m-Y',
            'orden_fecha_inicio'    => 'required|date_format:d-m-Y',
            'orden_fecha_compromiso'=> 'required|date_format:d-m-Y',
            'orden_ciudad_id'       => 'required|in:'.implode(',',array_keys(\Config::get('options.ciudad'))),
            'orden_provincia_id'    => 'required||in:'.implode(',',array_keys(\Config::get('options.provincia'))),
            'productos'             => 'required'
        ];
    }

    public function messages()
    {
        return [
            //
            'orden_number.required'             => 'El número de Orden de Producción es obligatorio',
            'orden_number.exists'               => 'La Orden de Producción que intenta editar no existe',
            'orden_lote_id.required'            => 'Debes seleccionar un Lote',
            'orden_lote_id.exists'              => 'Ha ocurrido un error',
            'orden_cliente_id.required'         => 'Debes seleccionar un Cliente',
            'orden_descripcion.required'        => 'La Descripción es obligatoria',
            'orden_fecha.required'              => 'Debes seleccionar una Fecha para la orden',
            'orden_fecha_inicio.required'       => 'Debes seleccionar una Fecha de inicio',
            'orden_fecha_compromiso.required'   => 'Debes seleccionar una Fecha de compromiso',
            'orden_fecha.date_format'           => 'La Fecha de orden tiene un formato incorrecto',
            'orden_fecha_inicio.date_format'    => 'La Fecha de inicio tiene un formato incorrecto',
            'orden_fecha_compromiso.date_format'=> 'La Fecha de compromiso tiene un formato incorrecto',
            'orden_ciudad_id.required'          => 'Debes seleccionar una Ciudad',
            'orden_provincia_id.required'       => 'Debes seleccionar una Provincia',
            'productos.required'                => 'Debes seleccionar Productos'
        ];
    }
}
