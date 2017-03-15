<div class="modal fade" id="modal_add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Agregar Procesadora</h4>
                <p class="alert alert-danger"></p>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => 'admin/elaborador/store',
                  'class' => 'form-horizontal',
                  'method' => 'POST',
                  'id' => 'form-add']) !!}
                  
                  @include('admin.elaborador.fields')

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
              <button id="save" type="button" class="btn btn-primary">Agregar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->