<div class="modal fade" id="modal_cancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Anular Etiqueta</h4>
                <p class="alert alert-danger"></p>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => 'almacenamiento/etiqueta/destroy',
                  'class' => 'form-horizontal',
                  'method' => 'POST',
                  'id' => 'form-delete']) !!}

                  {!! Form::hidden('etiqueta_id', null) !!}
                  
                  <div class="form-group">
                    {!! Form::label('nombre', 'Código',
                    ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-7">
                    {!! Form::text('etiqueta_barcode', null, ['class' => 'form-control', 'id' => 'etiqueta_barcode', 'disabled']) !!}
                    </div>
                  </div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
              <button id="delete" type="button" class="btn btn-danger">Anular</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->