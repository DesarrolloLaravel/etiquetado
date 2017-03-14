<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class LoginRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(!$this->request->get('g-recaptcha-response'))
        {
            return [
                //
                'g-recaptcha-response' => 'required'
            ];
        }
        else
        {
            return [
                //
                'usuario' => 'required', 
                'contrasena' => 'required',
                'g-recaptcha-response' => 'recaptcha'
            ];
        }
    }

    public function messages()
    {
        return [
            //
            'usuario.required' => 'El campo Usuario es obligatorio',
            'contrasena.required' => 'El campo Contraseña es obligatorio',
            'g-recaptcha-response.required' => 'Debes verificar que eres humano',
            'g-recaptcha-response.recaptcha' => 'El Captcha es incorrecto',
        ];
    }
}
