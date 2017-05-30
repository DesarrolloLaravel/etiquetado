<div class="modal fade" id="modal_idioma">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Selecciona Idioma para Reimprimir</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        {!! Form::label('idioma', 'Idioma',
                        ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-7">
                            {!! Form::select('idioma',
                            \Config::get('options.idioma'),
                            null,
                            ['class' => 'form-control', 'id' => 'idioma']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center">
                <button id="reprint_final" type="button" class="btn btn-primary">REIMPRIMIR</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->