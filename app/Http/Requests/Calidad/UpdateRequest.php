<?php

namespace App\Http\Requests\Calidad;

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
            'calidad_nombre'    =>'required|unique:calidad,calidad_nombre,'
                .$this->request->get('calidad_id').',calidad_id'
        ];
    }

    public function messages()
    {
        return [
            'calidad_nombre.required'   => 'El nombre es obligatorio',
            'calidad_nombre.unique'   => 'Ya existe otra Calidad con ese nombre. Por favor verifica tu información',
        ];
    }
}
