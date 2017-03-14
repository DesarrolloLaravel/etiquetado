<?php

namespace App\Http\Requests\OrdenDespacho;

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
            'cliente_id'        => 'required|exists:cliente,cliente_id',
            'orden_tipo'        => 'required|in:'.implode(',',array_keys(\Config::get('options.estado_despacho'))),
            'orden_guia'        => 'unique:orden_despacho,orden_guia',
            'orden_fecha'       => 'date_format:d-m-Y'
        ];
    }

    public function messages()
    {
        return [
            //
            'cliente_id.required'       => 'Debes seleccionar un Cliente',
            'cliente_id.in'             => 'Debes seleccionar un Cliente.',
            'orden_tipo.required'       => 'Debes seleccionar el estado de la Orden',
            'orden_tipo.in'             => 'Ha ocurrido un error. Inténtalo más tarde.',
            'orden_guia.unique'         => 'Ya existe un despacho asociado a ese N° de Guía',
            'orden_fecha.date_format'   => 'La Fecha de la Orden tiene un formato incorrecto'
        ];
    }
}
