<div class="modal fade" id="modal_edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar Orden de Producci&oacute;n</h4>
                <p class="alert alert-danger"></p>
            </div>
            <div class="modal-body row">
                {!! Form::open(['url' => 'admin/ordenproduccion/update',
                  'class' => 'form-horizontal',
                  'method' => 'POST',
                  'id' => 'form-edit']) !!}

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
              <button id="update" type="button" class="btn btn-primary">Actualizar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->