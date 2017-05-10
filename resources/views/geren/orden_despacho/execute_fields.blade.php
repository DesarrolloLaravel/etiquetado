<div class="col-sm-12">
	<div class="col-sm-5">
        <div class="form-group">
            {!! Form::label('etiqueta_lote_id', 'Orden de Despacho',
                ['class' => 'control-label']) !!}
            <div class="input-group">
                <input name="orden_id" id="orden_number" type="text" class="form-control" disabled>
                <span class="input-group-btn">
                    <button id="orden_search" class="btn btn-secondary" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="form-group" style="text-align:center">
            <a id="delete_despacho" class="btn btn-lg btn-danger">
                Eliminar Orden
            </a>
        </div>
    </div>
    <div class="col-sm-7">
    	<h4 style="text-align:center">Información Orden de Despacho</h4>
        {!! Form::open(['class' => 'col-sm-12 form-horizontal',
            'id' => 'form-summary']) !!}

            <div class="form-group">
                {!! Form::label('orden_estado', 'Estado',
                ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                {!! Form::label('0', '-',
                    ['class' => 'control-label', 'id' => 'orden_estado']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('orden_cliente', 'Cliente',
                ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                {!! Form::label('0', '-',
                    ['class' => 'control-label', 'id' => 'orden_cliente']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('orden_guia', 'Guía',
                ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                {!! Form::label('0', '-',
                    ['class' => 'control-label', 'id' => 'orden_guia']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('orden_fecha', 'Fecha',
                ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                {!! Form::label('0', '-',
                    ['class' => 'control-label', 'id' => 'orden_fecha']) !!}
                </div>
            </div>

            <div class="form-group">
                <table id="detail" class="col-sm-12 table">
                    <thead>
                        <th>N° Lote</th>
                        <th>Producto</th>
                        <th>Cajas Plan.</th>
                        <th>Kilos Plan.</th>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th colspan="1" style="text-align:right">Total:</th>
                            <th id="total_cajas"></th>
                            <th id="total_kilos"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        {!! Form::close() !!}
    </div>
</div>