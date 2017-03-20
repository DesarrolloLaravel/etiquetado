<?php

namespace App\Http\Requests\Cliente;

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
            'cliente_nombre'   =>'required',
            'cliente_nombre'    =>'required|unique:cliente,cliente_nombre,'
                .$this->request->get('cliente_id').',cliente_id'
        ];
        
    }

    public function messages()
    {
        return [
            'cliente_nombre.required'      =>'El nombre es obligatorio',
            'cliente_nombre.unique'         =>'Ya existe otro Cliente con ese nombre. Por favor verifique su información'      
        ];
    }
}
