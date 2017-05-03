<?php

namespace App\Http\Requests\nordic;

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
            'lote_id'               => 'required|exists:lote,lote_id',
            'etiqueta_fecha'        => 'required|date_format:d-m-Y',
            'orden_id'              => 'required|exists:orden_trabajo,orden_trabajo_id'
        ];
    }

    public function messages()
    {
        return [
            //
            'etiqueta_year.required'    => 'El Año es obligatorio',
            'lote_id.required'          => 'El Lote es obligatorio',
            'lote_id.exists'            => 'Ha ocurrido un error',
            'etiqueta_fecha.required'   => 'La Fecha es obligación',
            'etiqueta_fecha.date_format'=> 'La Fecha tiene un formato incorrecto',
            'orden_id.required'         => 'La Orden es obligatoria',
            'orden_id.exists'           => 'Ha ocurrido un error',
            'orden_productos.required'  => 'Debe seleccionar un producto',
            'orden_productos.exists'    => 'Ha ocurrido un error',
            'peso_real.required'        => 'El Peso Real es obligatorio',
            'peso_bruto.required'       => 'El Peso Bruto es obligatorio',
            'unidades.required'         => 'Las Unidades son obligatorias',
            'caja_number.required'      => 'El Número de Caja es obligatorio',
            'caja_number.unique'        => 'Ya existe existe una caja creada para este Lote'
        ];
    }
}
