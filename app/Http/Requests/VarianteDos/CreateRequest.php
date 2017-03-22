<?php

namespace App\Http\Requests\VarianteDos;

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
            'varianteDos_name' => 'required|max:255'
            
        ];
        
    }

    //funcion donde se definen los distintos mensajes del sistema
    public function messages()
    {
        return [
            //
            'varianteDos_name.required' => 'El campo Variante es obligatorio',
            
        ];
    }
}
