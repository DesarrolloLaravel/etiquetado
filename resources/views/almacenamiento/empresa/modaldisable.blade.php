<div class="modal fade" id="modal_delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminar Procesadora</h4>
                <p class="alert alert-danger"></p>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => 'almacenamiento/empresa/delete',
                  'class' => 'form-horizontal',
                  'method' => 'POST',
                  'id' => 'form-delete']) !!}
                  
                  @include('almacenamiento.empresa.fields')

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
              <button id="delete" type="button" class="btn btn-danger">Eliminar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->