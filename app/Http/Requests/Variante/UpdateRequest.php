<?php

namespace App\Http\Requests\Productor;

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
            'productor_name'   =>'required',
            'productor_name'    =>'required|unique:productor,productor_name,'
                .$this->request->get('productor_id').',productor_id'
        ];
        
    }

    public function messages()
    {
        return [
            'productor_name.required'      =>'El nombre es obligatorio',
            'productor_name.unique'         =>'Ya existe otro Productor con ese nombre. Por favor verifique su información'      
        ];
    }
}
