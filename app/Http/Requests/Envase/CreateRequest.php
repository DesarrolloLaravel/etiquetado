<?php

namespace App\Http\Requests\Envase;

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
            'envase_nombre' => 'required|max:255',
            'envase_capacidad' => 'required',
            'envase_nombre' => 'required|unique:envase,envase_nombre'
        ];
        
    }

    //funcion donde se definen los distintos mensajes del sistema
    public function messages()
    {
        return [
            //
            'envase_nombre.required' => 'El campo Nombre es obligatorio',
            'envase_capacidad.required' => 'El campo Capacidad es obligatorio',
            'envase_nombre.unique' => 'Ya existe otro Envase con ese nombre, por favor verifique su información'
        ];
    }
}
