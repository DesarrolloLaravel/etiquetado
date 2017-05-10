<div class="modal fade" id="modal_despacho">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Despacho Caja</h4>
                <p class="alert alert-success" id="alert-success-modal"></p>
                <p class="alert alert-danger" id="alert-danger-modal"></p>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => 'geren/caja/show',
                  'class' => 'form-horizontal',
                  'method' => 'GET',
                  'id' => 'form-show']) !!}

                <div class="form-group">
                    {!! Form::label('nombre', 'Código',
                    ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-7">
                    {!! Form::text('etiqueta_barcode', null, ['class' => 'form-control',
                    'id' => 'etiqueta_barcode']) !!}
                    </div>
                </div>

                <div class="modal-footer" style="text-align:center">
                    <button id="show" type="button" class="btn btn-lg btn-primary">OK</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->