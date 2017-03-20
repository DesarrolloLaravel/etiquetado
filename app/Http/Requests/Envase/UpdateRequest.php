<?php

namespace App\Http\Requests\Envase;

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
            'envase_nombre'   =>'required',
            'envase_nombre'    =>'required|unique:envase,envase_nombre,'
                .$this->request->get('envase_id').',envase_id',
            'envase_capacidad' => 'required'
        ];
       
    }

    public function messages()
    {
        return [
            'envase_nombre.required'      =>'El nombre es obligatorio',
            'envase_capacidad.required'       =>'La Capacidad es obligatoria',
            'envase_nombre.unique'         =>'Ya existe otro Envase con ese nombre. Por favor verifique su información'
        ];
    }

}
