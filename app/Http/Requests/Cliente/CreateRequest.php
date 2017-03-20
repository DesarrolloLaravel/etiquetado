<?php

namespace App\Http\Requests\Cliente;

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
            'cliente_nombre' => 'required|max:255'
            
        ];
        
    }

    //funcion donde se definen los distintos mensajes del sistema
    public function messages()
    {
        return [
            //
            'cliente_nombre.required' => 'El campo Nombre es obligatorio',
            
        ];
    }
}
