@extends('partials.modal')

@section('modal-id'){{"modal_add"}}@overwrite

@section('modal-title'){{"Agregar Cliente"}}@overwrite
@section('modal-body')  
  <div class="modal-body">
      {!! Form::open(['url' => 'almacenamiento/cliente/store',
        'class' => 'form-horizontal',
        'method' => 'POST',
        'id' => 'form-add']) !!}
        
        @include('almacenamiento.cliente.fields')

      {!! Form::close() !!}
  </div>
@overwrite
@section('modal-footer')
  <div class="modal-footer">
    <button id="save" type="button" class="btn btn-primary">Agregar</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  </div>
@overwrite

        