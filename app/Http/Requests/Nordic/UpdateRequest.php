<?php

namespace App\Http\Requests\Etiqueta;

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
        //dd($this->request->get('etiqueta_barcode'));
        //dd($this->request->all());
        return [
            //
            'etiqueta_barcode' => 'required|exists:etiqueta,etiqueta_barcode',
            'select_posicion' => 'required|exists:posicion,posicion_id'
        ];
    }

    public function messages()
    {
        return [
            'etiqueta_barcode.required' => 'El Código es obligatorio',
            'etiqueta_barcode.exists' => 'El Código ingresado no existe. Verifica tu información.',
            'select_posicion.required' => 'Debes completar el formulario para recepcionar una caja',
            'select_posicion.exists' => 'Ha ocurrido un error. Inténtalo más tarde'
        ];
    }
}
