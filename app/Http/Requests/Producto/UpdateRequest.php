<?php

namespace App\Http\Requests\Producto;

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
            'producto_nombre'   =>'required',
            'producto_codigo' => 'required|unique:producto,producto_codigo,'
                .$this->request->get('producto_id').',producto_id',
            'producto_nombre'    =>'required|unique:producto,producto_nombre,'
                .$this->request->get('producto_id').',producto_id'
        ];
        
    }

    public function messages()
    {
        return [
            'producto_nombre.required'      =>'El Producto es obligatorio',
            'producto_nombre.unique'         =>'Ya existe otro Producto con ese nombre. Por favor verifique su información',
            'producto_codigo.required' => 'El código de Producto es obligatorio',
            'producto_codigo.unique' => 'Ya existe otro Producto con ese código. Por favor verifique su información'      
        ];
    }
}
