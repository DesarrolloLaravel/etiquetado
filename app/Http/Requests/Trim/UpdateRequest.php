<?php

namespace App\Http\Requests\Trim;

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
            'trim_name'   =>'required',
            'trim_name'    =>'required|unique:trim,trim_name,'
                .$this->request->get('trim_id').',trim_id'
        ];
        
    }

    public function messages()
    {
        return [
            'trim_name.required'      =>'Trim es obligatorio',
            'trim_name.unique'         =>'Ya existe otra Trim con ese nombre. Por favor verifique su información'      
        ];
    }
}
