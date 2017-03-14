<?php

namespace App\Http\Requests\Procesador;

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
        if(!$this->request->get('procesador_rut'))
        {
            return [
                //
                'procesador_rut' => 'different:false'
            ];
        }
        else
        {
            return [ 
                'procesador_name'   =>'required',
                'procesador_rut'    =>'required|unique:procesador,procesador_rut,'
                    .$this->request->get('procesador_id').',procesador_id'
            ];
        }
    }

    public function messages()
    {
        return [
            'procesador_name.required'      =>'El nombre es obligatorio',
            'procesador_rut.required'       =>'El RUT es obligatorio',
            'procesador_rut.unique'         =>'Ya existe otra empresa con ese RUT. Por favor verifique su información',
            'procesador_rut.different'    => 'El RUT es incorrecto'            
        ];
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();
        $rut = RutGenerator::validarRut($data['procesador_rut']);
        $data['procesador_rut'] = $rut;
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
