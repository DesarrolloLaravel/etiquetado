<div class="modal fade" id="modal_delete">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cerrar Lote</h4>
                <p class="alert alert-danger"></p>
            </div>
            <div class="modal-body row">
                {!! Form::open(['url' => 'geren/lote/delete',
                  'method' => 'POST',
                  'id' => 'form-delete']) !!}

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
              <button id="delete" type="button" class="btn btn-danger">Cerrar Lote</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->