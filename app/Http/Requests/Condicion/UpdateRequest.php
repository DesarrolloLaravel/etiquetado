<?php

namespace App\Http\Requests\Condicion;

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
            'condicion_name'   =>'required',
            'condicion_name'    =>'required|unique:condicion,condicion_name,'
                .$this->request->get('condicion_id').',condicion_id'
        ];
        
    }

    public function messages()
    {
        return [
            'condicion_name.required'      =>'La Condición es obligatoria',
            'condicion_name.unique'         =>'Ya existe otra Condicion con ese nombre. Por favor verifique su información'      
        ];
    }
}
