<?php

namespace App\Http\Requests\OrdenTrabajo;

use App\Http\Requests\Request;

class ReturnRequest extends Request
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
            'orden_kilos_retornar'    => 'required',
            'orden_cajas_retornar'     => 'required'
        ];
    }

    public function messages()
    {
        return [
            //
            'orden_kilos_retornar.required'   => 'Debes añadir kilos a retornar',
            'orden_cajas_retornar.required'   => 'Debes añadir cajas a retornar'
        ];
    }
}
