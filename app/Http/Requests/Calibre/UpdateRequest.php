<?php

namespace App\Http\Requests\Calibre;

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
            //
            'calibre_nombre'    =>'required|unique:calibre,calibre_nombre,'
                .$this->request->get('calibre_id').',calibre_id'
        ];
    }

    public function messages()
    {
        return [
            'calibre_nombre.required'   => 'El nombre es obligatorio',
            'calibre_nombre.unique'   => 'Ya existe otro Calibre con ese nombre. Por favor verifica tu información',
        ];
    }
}
