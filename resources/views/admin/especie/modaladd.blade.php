@extends('partials.modal')

@section('modal-id'){{"modal_add"}}@overwrite

@section('modal-title'){{"Agregar Especie"}}@overwrite
@section('modal-body')  
  <div class="modal-body">
      {!! Form::open(['url' => 'admin/especie/store',
        'class' => 'form-horizontal',
        'method' => 'POST',
        'id' => 'form-add']) !!}
        
        @include('admin.especie.fields')

      {!! Form::close() !!}
  </div>
@overwrite
@section('modal-footer')
  <div class="modal-footer">
    <button id="save" type="button" class="btn btn-primary">Agregar</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  </div>
@overwrite

        