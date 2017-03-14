<?php

namespace App\Http\Requests\Producto;

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
            'producto_nombre' => 'required|unique:producto,producto_nombre',
            'producto_codigo' => 'unique:producto,producto_codigo'
        ];
    }
}
