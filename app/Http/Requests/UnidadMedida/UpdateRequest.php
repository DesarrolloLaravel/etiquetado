<?php

namespace App\Http\Requests\UnidadMEdida;

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
            //
            'unidad_medida_nombre'    =>'required|unique:unidad_medida,unidad_medida_nombre,'
                .$this->request->get('unidad_medida_nombre').',unidad_medida_nombre',
            'unidad_medida_abreviacion'    =>'required|unique:unidad_medida,unidad_medida_abreviacion,'
                .$this->request->get('unidad_medida_nombre').',unidad_medida_nombre'
        ];
    }

    public function messages()
    {
        return [
            'unidad_medida_nombre.required'   => 'El nombre es obligatorio',
            'unidad_medida_nombre.unique'   => 'Ya existe otra Unidad de Medidad con ese Nombre. Por favor verifica tu información',
            'unidad_medida_abreviacion.required'   => 'El nombre es obligatorio',
            'unidad_medida_abreviacion.unique'   => 'Ya existe otra Unidad de Medidad con esa Abreviatura. Por favor verifica tu información',
        ];
    }
}
