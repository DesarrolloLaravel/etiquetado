<?php

namespace App\Http\Requests\Especie;

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
            'especie_name' => 'required|unique:especie,especie_name',
            'especie_comercial_name' => 'required|unique:especie,especie_comercial_name',
            'especie_abbreviation' => 'required|unique:especie,especie_abbreviation',
        ];
    }
}
