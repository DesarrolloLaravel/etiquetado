@extends('partials.modal')

@section('modal-id'){{"modal_error"}}@overwrite

@section('modal-title'){{"Advertencia"}}@overwrite
@section('modal-body') 
    <div class="modal-body">
        <div class="col-sm-12">
          <h4>No puede editar o eliminar esta VARIANTE</h4>
          <h4>Bajo política de integridad del sistema </h4>
        </div>
    </div>
@overwrite
@section('modal-footer')
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    </div>
@overwrite
        