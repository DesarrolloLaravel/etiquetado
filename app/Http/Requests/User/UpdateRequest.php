<?php

namespace App\Http\Requests\User;

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
            'users_name' => 'required|max:255',
            'users_email' => 'required|email',
            'users_user' => 'required',
            'users_role' => 'required'
        ];
        
    }

    public function messages()
    {
        return [
            'users_name.required' => 'El campo Nombre es obligatorio',
            'users_email.required' => 'El campo Email es obligatorio',
            'users_email.email' => 'El campo Email debe ser valido',
            'users_user.required' => 'El campo ID-Usuario es obligatorio',
            'users_role.required' => 'El Rol es requerido'      
        ];
    }
}
