<?php

namespace App\Http\Requests\Elaborador;

use App\Http\Requests\Request;
use App\FakerExtend\RutGenerator;

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
        if(!$this->request->get('elaborador_rut'))
        {
            return [
                //
                'elaborador_rut' => 'different:false'
            ];
        }
        else
        {
            return [ 
                'elaborador_name'   =>'required',
                'elaborador_rut'    =>'required|unique:elaborador,elaborador_rut,'
                    .$this->request->get('elaborador_id').',elaborador_id'
            ];
        }
    }

    public function messages()
    {
        return [
            'elaborador_name.required'      =>'El nombre es obligatorio',
            'elaborador_rut.required'       =>'El RUT es obligatorio',
            'elaborador_rut.unique'         =>'Ya existe otro Elaborador con ese RUT. Por favor verifique su información',
            'elaborador_rut.different'    => 'El RUT es incorrecto'            
        ];
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();
        $rut = RutGenerator::validarRut($data['elaborador_rut']);
        $data['elaborador_rut'] = $rut;
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
