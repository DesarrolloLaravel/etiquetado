<div class="col-sm-12">
	<div class="col-sm-3">
		<div class="form-group">
			{!! Form::label('cliente_id', 'Cliente',
				['class' => 'control-label']) !!}
			{!! Form::select('cliente_id', $clientes, null,['class' => 'form-control','id' => 'select_cliente']) !!}
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			{!! Form::label('orden_tipo', 'Estado Orden',
				['class' => 'control-label']) !!}
			{!! Form::select('orden_tipo', array_except(\Config::get('options.estado_despacho'),0), null,['class' => 'form-control','id' => 'select_tipo']) !!}
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			{!! Form::label('orden_guia', 'N° Guía',
				['class' => 'control-label']) !!}
			{!! Form::text('orden_guia', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			{!! Form::label('nombre', 'Fecha Despacho',
				['class' => 'control-label']) !!}
			{!! Form::text('orden_fecha', null, ['class' => 'form-control datepicker']) !!}
		</div>
	</div>
</div>
<div class="col-sm-12">
	<h4>Detalle</h4>
	<hr>
	<div class="col-sm-3">
		<div class="form-group">
			{!! Form::label('etiqueta_lote_id', 'Lote',
				['class' => 'control-label']) !!}
			<div class="input-group">
				<input name="lote_id" id="lote_id" type="text" class="form-control" disabled>
				<span class="input-group-btn">
					<button id="lote_search" class="btn btn-secondary" type="button">
						<i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('producto_detail', 'Cajas Plan.',
				['class' => 'control-label']) !!}
			{!! Form::number('cajas_plan', null, ['class' => 'form-control', 'id' => 'cajas_plan','min' => 0]) !!}
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			{!! Form::label('producto', 'Producto',
				['class' => 'control-label']) !!}
			{!! Form::select('orden_productos', [], null,['class' => 'form-control select2','id' => 'select_productos']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('producto_detail', 'Kilos Plan.',
				['class' => 'control-label']) !!}
			{!! Form::number('kilos_plan', null, ['class' => 'form-control', 'id' => 'kilos_plan', 'min' => 0]) !!}
		</div>
	</div>
	<div class="col-sm-5" style="text-align:center">
		<h4>Informaci&oacute;n de Lote</h4>
		{!! Form::open(['class' => 'form-horizontal',
			'id' => 'form-summary']) !!}

		    <div class="form-group">
		        {!! Form::label('lote', 'Lote',
		        ['class' => 'col-sm-6 control-label']) !!}
		        <div class="col-sm-6">
		        {!! Form::label('0', 0,
		            ['class' => 'control-label', 'id' => 'n_lote']) !!}
		        </div>
		    </div>

		    <div class="form-group">
		        {!! Form::label('n_cajas', 'Cajas Disp.',
		        ['class' => 'col-sm-6 control-label']) !!}
		        <div class="col-sm-6">
		        {!! Form::label('0', 0,
		            ['class' => 'control-label', 'id' => 'n_cajas']) !!}
		        </div>
		    </div>

		    <div class="form-group">
		        {!! Form::label('total_peso_real', 'Kilos Disp.',
		        ['class' => 'col-sm-6 control-label']) !!}
		        <div class="col-sm-6s">
		        {!! Form::label('0', 0,
		            ['class' => 'control-label', 'id' => 'total_peso_real']) !!}
		        </div>
		    </div>

		{!! Form::close() !!}
	</div>
	<div class="col-sm-1">
		<br><br><br>
		<a id="add-detail" class="btn btn-primary btn-lg">
	    	<i class="fa fa-plus"></i>
	    </a>
	</div>
</div>
<div class="col-sm-12">
	<hr>
	<table class="table table-bordered" id="table-detail" width="100%">
        <thead>
            <tr>
            	<th>Lote</th>
            	<th>Producto ID</th>
            	<th>Producto</th>
                <th>Cajas</th>
                <th>Kilos</th>
                <th>Opci&oacute;n</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Total:</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>