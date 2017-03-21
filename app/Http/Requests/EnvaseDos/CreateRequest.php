<?php

namespace App\Http\Requests\EnvaseDos;

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
            'envaseDos_nombre' => 'required|max:255',
            'envaseDos_capacidad' => 'required',
            'envaseDos_nombre' => 'required|unique:envaseDos,envaseDos_nombre'
        ];
        
    }

    //funcion donde se definen los distintos mensajes del sistema
    public function messages()
    {
        return [
            //
            'envaseDos_nombre.required' => 'El campo Nombre es obligatorio',
            'envaseDos_capacidad.required' => 'El campo Capacidad es obligatorio',
            'envaseDos_nombre.unique' => 'Ya existe otro Envase con ese nombre, por favor verifique su información'
        ];
    }
}
