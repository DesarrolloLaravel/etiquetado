<?php

namespace App\Http\Requests\EnvaseDos;

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
            'envaseDos_nombre'   =>'required',
            'envaseDos_nombre'    =>'required|unique:envaseDos,envaseDos_nombre,'
                .$this->request->get('envaseDos_id').',envaseDos_id',
            'envaseDos_capacidad' => 'required'
        ];
       
    }

    public function messages()
    {
        return [
            'envaseDos_nombre.required'      =>'El nombre es obligatorio',
            'envaseDos_capacidad.required'       =>'La Capacidad es obligatoria',
            'envaseDos_nombre.unique'         =>'Ya existe otro Envase con ese nombre. Por favor verifique su información'
        ];
    }

}
