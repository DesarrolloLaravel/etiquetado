<?php

namespace App\Http\Requests\Formato;

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
            'formato_nombre'    =>'required|unique:formato,formato_nombre,'
                .$this->request->get('formato_nombre').',formato_nombre',
            'formato_abreviacion'    =>'required|unique:formato,formato_abreviacion,'
                .$this->request->get('formato_nombre').',formato_nombre'
        ];
    }

    public function messages()
    {
        return [
            'formato_nombre.required'   => 'El nombre es obligatorio',
            'formato_nombre.unique'   => 'Ya existe otro Formato con ese Nombre. Por favor verifica tu información',
            'formato_abreviacion.required'   => 'El nombre es obligatorio',
            'formato_abreviacion.unique'   => 'Ya existe otro Formato con esa Abreviatura. Por favor verifica tu información',
        ];
    }
}
