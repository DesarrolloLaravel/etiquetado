<?php

namespace App\Http\Requests\Formato;

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
            'formato_nombre' => 'required|unique:formato,formato_nombre',
            'formato_abreviatura' => 'required|unique:formato,formato_abreviatura',
        ];
    }
}
