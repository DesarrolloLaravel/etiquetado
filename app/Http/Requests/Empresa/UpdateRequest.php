<?php

namespace App\Http\Requests\Empresa;

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
        if(!$this->request->get('empresa_rut'))
        {
            return [
                //
                'empresa_rut' => 'different:false'
            ];
        }
        else
        {
            return [ 
                'empresa_name'   =>'required',
                'empresa_rut'    =>'required|unique:empresa,empresa_rut,'
                    .$this->request->get('empresa_id').',empresa_id'
            ];
        }
    }

    public function messages()
    {
        return [
            'empresa_name.required'      =>'El nombre es obligatorio',
            'empresa_rut.required'       =>'El RUT es obligatorio',
            'empresa_rut.unique'         =>'Ya existe otra empresa con ese RUT. Por favor verifique su información',            
            'empresa_rut.different' => 'El RUT es incorrecto'
        ];
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();
        $rut = RutGenerator::validarRut($data['empresa_rut']);
        $data['empresa_rut'] = $rut;
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
