@extends('partials.modal')

@section('modal-id'){{"modal_edit"}}@overwrite

@section('modal-title'){{"Editar Calibre"}}@overwrite
@section('modal-body')
    <div class="modal-body row">
        {!! Form::open(['url' => 'geren/calibre/update',
          'class' => 'form-horizontal',
          'method' => 'POST',
          'id' => 'form-edit']) !!}

          @include('geren.calibre.fields')

        {!! Form::close() !!}
    </div>
@overwrite
@section('modal-footer')
    <div class="modal-footer">
        <button id="update" type="button" class="btn btn-primary">Actualizar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    </div>
@overwrite
