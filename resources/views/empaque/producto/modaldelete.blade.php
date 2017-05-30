@extends('partials.modal')

@section('modal-id'){{"modal_delete"}}@overwrite

@section('modal-title'){{"Eliminar Producto"}}@overwrite
@section('modal-body')           
  <div class="modal-body">
      {!! Form::open(['url' => 'empaque/producto/delete',
        'class' => 'form-horizontal',
        'method' => 'POST',
        'id' => 'form-delete']) !!}
        
        @include('empaque.producto.fields')

      {!! Form::close() !!}
  </div>
@overwrite
@section('modal-footer')
  <div class="modal-footer">
    <button id="delete" type="button" class="btn btn-danger">Eliminar</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  </div>
@overwrite