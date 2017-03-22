<?php

namespace App\Http\Requests\VarianteDos;

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
            'varianteDos_name'   =>'required',
            'varianteDos_name'    =>'required|unique:varianteDos,varianteDos_name,'
                .$this->request->get('varianteDos_id').',varianteDos_id'
        ];
        
    }

    public function messages()
    {
        return [
            'varianteDos_name.required'      =>'La Variante es obligatoria',
            'varianteDos_name.unique'         =>'Ya existe otra Variante con ese nombre. Por favor verifique su información'      
        ];
    }
}
