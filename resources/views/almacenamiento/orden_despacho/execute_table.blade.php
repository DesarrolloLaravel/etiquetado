<div class="col-sm-12">
    <div class="col-sm-8">
        @if(\Route::getCurrentRoute()->getPath() != 'almacenamiento/despacho')
        <a class="btn btn-primary" id="add">
            <i class="fa fa-plus"></i>
            Despachar Cajas
        </a>
        <br><br>
        @endif
        <table class="table table-bordered" id="table-orden-detail" width="100%">
            <thead>
                <tr>
                    <th>N° Caja</th>
                    <th>Código</th>
                    <th>Kilos</th>
                    @if(\Route::getCurrentRoute()->getPath() != 'almacenamiento/despacho')
                    <th>Opción</th>
                    @endif
                    
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th colspan="1" style="text-align:right">Total:</th>
                    <th></th>
                    @if(\Route::getCurrentRoute()->getPath() != 'almacenamiento/despacho')
                    <th></th>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-sm-4">
        <h4 style="text-align:center">Información de Cajas</h4>
        {!! Form::open(['class' => 'col-sm-12 form-horizontal',
            'id' => 'form-detail']) !!}

            <div class="form-group">
                {!! Form::label('n_cajas_parcial', 'N° Parcial de Cajas',
                ['class' => 'col-sm-6 control-label']) !!}
                <div class="col-sm-6">
                {!! Form::label('0', '-',
                    ['class' => 'control-label', 'id' => 'n_cajas_parcial']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('orden_cliente', 'Total Cajas',
                ['class' => 'col-sm-6 control-label']) !!}
                <div class="col-sm-6">
                {!! Form::label('0', '-',
                    ['class' => 'control-label', 'id' => 'total_cajas_parcial']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('orden_cliente', 'Total Kilos',
                ['class' => 'col-sm-6 control-label']) !!}
                <div class="col-sm-6">
                {!! Form::label('0', '-',
                    ['class' => 'control-label', 'id' => 'total_kilos_parcial']) !!}
                </div>
            </div>
            <br>
            @if(\Route::getCurrentRoute()->getPath() != 'almacenamiento/despacho')
            <div class="form-group" style="text-align:center">
                <a id="save_detail" class="btn btn-primary">
                    Guardar
                </a>
            </div>
            @endif
            @if(\Auth::user()->users_role == "administracion" && \Route::getCurrentRoute()->getPath() != 'almacenamiento/despacho')
                <div class="form-group" style="text-align:center">
                    <a id="discount" class="btn btn-lg btn-success">
                        Despachar
                    </a>
                </div>
            @else
                <div class="form-group" style="text-align:center">
                    <a id="export" class="btn btn-lg btn-success">
                        Exportar Packing
                    </a>
                </div>
            @endif

        {!! Form::close() !!}
    </div>
</div>
