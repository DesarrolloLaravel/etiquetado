<div class="modal fade" id="modal_etiqueta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Retornar Etiqueta</h4>
                <p class="alert alert-danger"></p>
            </div>
            <div class="modal-body row">
                {!! Form::open(['url' => 'admin/ordentrabajo/r_pallet',
                  'class' => 'form-horizontal',
                  'method' => 'POST',
                  'id' => 'form-returne']) !!}

                <div class="col-sm-12">
                    <div class="form-group">
                      {!! Form::label('nombre', 'Num. Orden',
                        ['class' => 'col-sm-2 control-label']) !!}
                      <div class="col-sm-2">
                        {!! Form::number('orden_number', null, ['class' => 'form-control', 'id' => 'orden_trabajo_id', 'min' => 0, 'disabled']) !!}
                      </div>

                      {!! Form::hidden('orden_etid',null, ['id' => 'orden_etid']) !!}
                      

                      {!! Form::label('nombre', 'Etiqueta',
                        ['class' => 'col-sm-2 control-label']) !!}
                      <div class="col-sm-2">
                        {!! Form::text('orden_etiqueta', null, ['class' => 'form-control', 'id' => 'orden_etiqueta', 'min' => 0, 'disabled']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div class="form-group"> 
                        {!! Form::label('nombre', 'Kilos etiqueta',
                        ['class' => 'col-sm-2 control-label']) !!}
                      <div class="col-sm-2">
                        {!! Form::number('orden_kilos_actual', null, ['class' => 'form-control', 'id' => 'orden_peso_actual', 'min' => 0, 'disabled']) !!}
                      </div>

                      {!! Form::label('nombre', 'Cajas etiqueta',
                        ['class' => 'col-sm-2 control-label']) !!}
                      <div class="col-sm-2">
                        {!! Form::number('orden_cajas_actual', null, ['class' => 'form-control', 'id' => 'orden_cajas_actual', 'min' => 0, 'disabled']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div class="form-group">

                      {!! Form::label('nombre', 'Kilos retornar',
                        ['class' => 'col-sm-2 control-label']) !!}
                      <div class="col-sm-2">
                        {!! Form::number('orden_kilos_retornar',null, ['class' => 'form-control', 'id' => 'orden_kilos_retornar', 'min' => 0, ]) !!}
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div class="form-group">

                      {!! Form::label('nombre', 'Cajas retornar',
                        ['class' => 'col-sm-2 control-label']) !!}
                      <div class="col-sm-2">
                        {!! Form::number('orden_cajas_retornar',null, ['class' => 'form-control', 'id' => 'orden_cajas_retornar', 'min' => 0, ]) !!}
                      </div>
                    </div>
                  </div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
              <button id="update_return" type="button" class="btn btn-primary">Retornar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->