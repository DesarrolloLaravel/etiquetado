@extends('partials.modal')

@section('modal-id'){{"modal_edit"}}@overwrite

@section('modal-title'){{"Editar Producto"}}@overwrite
@section('modal-body')
  <div class="modal-body">
    {!! Form::open(['url' => 'admin/producto/update',
    'class' => 'form-horizontal',
    'method' => 'POST',
      'id' => 'form-edit']) !!}
      
      @include('admin.producto.fields')

    {!! Form::close() !!}
  </div>
@overwrite
@section('modal-footer')
  <div class="modal-footer">
    <button id="update" type="button" class="btn btn-primary">Actualizar</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  </div>
@overwrite

        