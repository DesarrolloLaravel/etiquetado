<?php

namespace App\Http\Requests\EtiquetaMP;

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

            'lote_id'               => 'required',
            'etiqueta_fecha'        => 'required|date_format:d-m-Y',
            'orden_productos_id'    => 'required',
            'peso_real'             => 'required',
            'unidades'              => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            //

            'lote_id.required'             => 'El Lote es obligatorio',
            'lote_id.exists'               => 'Ha ocurrido un error',
            'etiqueta_fecha.required'      => 'La Fecha es obligación',
            'etiqueta_fecha.date_format'   => 'La Fecha tiene un formato incorrecto',
            'orden_productos_id.required'  => 'Debe seleccionar un producto',
            'orden_productos_id.exists'    => 'Ha ocurrido un error',
            'peso_real.required'           => 'El Peso Pallet es obligatorio',
            'unidades.required'            => 'Las Unidades son obligatorias',
            'unidades.min'                 => 'Cantidad de Cajas incorrecta'
        ];
    }
}
