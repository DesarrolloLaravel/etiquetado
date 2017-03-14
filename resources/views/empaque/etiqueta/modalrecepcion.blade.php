<div class="modal fade" id="modal_recepcion">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Recepción Etiqueta</h4>
                <p class="alert alert-danger"></p>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => 'admin/etiqueta/update',
                  'class' => 'form-horizontal',
                  'method' => 'POST',
                  'id' => 'form-edit']) !!}

                <div class="form-group">
                    {!! Form::label('nombre', 'Código',
                    ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-7">
                    {!! Form::text('etiqueta_barcode', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer" style="text-align:center">
              <button id="update" type="button" class="btn btn-lg btn-primary">OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->