<?php

namespace App\Http\Requests\Variante;

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
            'variante_name'   =>'required',
            'variante_name'    =>'required|unique:variante,variante_name,'
                .$this->request->get('variante_id').',variante_id'
        ];
        
    }

    public function messages()
    {
        return [
            'variante_name.required'      =>'La Variante es obligatoria',
            'variante_name.unique'         =>'Ya existe otra Variante con ese nombre. Por favor verifique su información'      
        ];
    }
}
