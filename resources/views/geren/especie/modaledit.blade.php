@extends('partials.modal')

@section('modal-id'){{"modal_edit"}}@overwrite

@section('modal-title'){{"Editar Especie"}}@overwrite
@section('modal-body')
  <div class="modal-body">
      {!! Form::open(['url' => 'geren/especie/update',
        'class' => 'form-horizontal',
        'method' => 'POST',
        'id' => 'form-edit']) !!}
        
        @include('geren.especie.fields')

      {!! Form::close() !!}
  </div>
@overwrite
@section('modal-footer')
  <div class="modal-footer">
    <button id="update" type="button" class="btn btn-primary">Actualizar</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  </div>
@overwrite