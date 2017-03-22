<?php

namespace App\Http\Requests\UnidadMedida;

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
            //
            'unidad_medida_nombre' => 'required|unique:unidad_medida,unidad_medida_nombre',
            'unidad_medida_abreviacion' => 'required|unique:unidad_medida,unidad_medida_abreviacion',
        ];
    }
}
