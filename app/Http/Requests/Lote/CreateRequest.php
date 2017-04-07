<?php

namespace App\Http\Requests\Lote;

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
        //dd($this->request->get('lote_procesador_id'));
        return [
            'lote_tipo_id'          => 'required',
            'lote_year'             => 'required',
            'lote_procesador_id'    => 'required',
            'lote_elaborador_id'    => 'required',
            'lote_mp_id'            => 'required',
            'lote_especie_id'       => 'required',
            'lote_calidad_id'       => 'required',
            'lote_fecha_documento'  => 'required|date_format:d-m-Y|before:lote_fecha_planta',
            'lote_fecha_planta'     => 'required|date_format:d-m-Y|before:lote_fecha_expiracion',
            'lote_fecha_expiracion' => 'required|date_format:d-m-Y',
            'lote_n_documento'      => 'required',
            'lote_productor_id'     => 'required',
            'lote_destino_id'       => 'required',
            'lote_condicion'        => 'required',
            'lote_kilos_declarado'  => 'required',
            'lote_kilos_recepcion'  => 'required',
            'lote_cajas_declarado'  => 'required',
            'lote_cajas_recepcion'  => 'required',
        ];
    }

    //funcion donde se definen los distintos mensajes del sistema
    public function messages()
    {
        return [
            //
            'lote_tipo_id.required' => 'Debes seleccionar un Tipo de Lote',
            'lote_year.required' => 'El campo Año es obligatorio',
            'lote_procesador_id.required' => 'Debes seleccionar un Procesador',
            'lote_elaborador_id.required' => 'Debes seleccionar un Elaborador',
            'lote_mp_id.required' => 'Debes seleccionar una Materia Prima',
            'lote_especie_id.required' => 'Debes seleccionar una Especie',
            'lote_calidad_id.required' => 'Debes seleccionar un tipo de Calidad',
            'lote_fecha_documento.required' => 'Debes seleccionar una Fecha de Guía/Factura',
            'lote_fecha_documento.date_format' => 'Fecha de Guía/Factura tiene un formato incorrecto',
            'lote_fecha_documento.before' => 'Fecha de Guía/Factura tiene que ser anterior a Fecha de Ingreso a Planta',
            'lote_fecha_planta.required' => 'Fecha de Ingreso a Planta es obligatorio',
            'lote_fecha_planta.date_format' => 'Fecha de Ingreso a Planta tiene un formato incorrecto',
            'lote_fecha_planta.before' => 'Fecha de Ingreso a Planta tiene que ser anterior a Fecha de Vencimiento',
            'lote_fecha_expiracion.required' => 'Fecha de Vencimiento es obligatorio',
            'lote_fecha_expiracion.date_format' => 'Fecha de Vencimiento tiene un formato incorrecto',
            'lote_n_documento.required' => 'El N° de Guía/Factura es obligatorio',
            'lote_kilos.required' => 'El campo Kilos es obligatorio',
            'lote_productor_id.required' => 'Debes seleccionar un Productor',
            'lote_destino_id.required' => 'Debes seleccionar un Destino',
            'lote_condicion.required' => 'Debes elegir una Condición',
            'lote_kilos_declarado.required'  => 'El campo Kilos Declarados es obligatorio',
            'lote_kilos_recepcion.required'  => 'El campo Kilos Recepción es obligatorio',
            'lote_cajas_declarado.required'  => 'El campo Cajas Declaradas es obligatorio',
            'lote_cajas_recepcion.required'  => 'El campo Cajas Recepción es obligatorio'
        ];
    }
}
