<?php

namespace App\Http\Requests\Especie;

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
            'especie_name'    =>'required|unique:especie,especie_name,'
                .$this->request->get('especie_id').',especie_id',
            'especie_comercial_name'    =>'required|unique:especie,especie_comercial_name,'
                .$this->request->get('especie_id').',especie_id',
            'especie_abbreviation'    =>'required|unique:especie,especie_abbreviation,'
                .$this->request->get('especie_id').',especie_id'
        ];
    }

    public function messages()
    {
        return [
            'especie_name.required'   => 'El nombre es obligatorio',
            'especie_name.unique'   => 'Ya existe otra Especie con ese Nombre. Por favor verifica tu información',
            'especie_comercial_name.required'   => 'El nombre es obligatorio',
            'especie_comercial_name.unique'   => 'Ya existe otra Especie con ese Nombre Comercial. Por favor verifica tu información',
            'especie_abbreviation.required'   => 'El nombre es obligatorio',
            'especie_abbreviation.unique'   => 'Ya existe otra Especie con esa Abreviatura. Por favor verifica tu información',
        ];
    }
}
