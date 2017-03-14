@section('contentPanel')
<div class="form-group">

{!! Form::label('lote', 'Lote',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-4">
		{!! Form::select('orden_lote_id',
							$lotes,
							null, 
							['class' => 'form-control']) !!}
	</div>

	{!! Form::label('nombre', 'Num. Orden',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-4">
		{!! Form::number('orden_number', $proxima_orden, ['class' => 'form-control',
					'min' => 0, 'disabled']) !!}
	</div>
	
</div>
<div class="form-group">

	{!! Form::label('orden_cliente', 'Cliente',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-4">
		{!! Form::select('orden_cliente_id', 
							\Config::get('options.cliente'),
							null, 
							['class' => 'form-control']) !!}
	</div>

	{!! Form::label('descripcion', 'Descripción',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-4">
		{!! Form::text('orden_descripcion', null, ['class' => 'form-control']) !!}
	</div>
	
</div>
<div class="form-group">

	{!! Form::label('fecha', 'Fecha Orden',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-2">
		{!! Form::text('orden_fecha', null, ['class' => 'form-control datepicker']) !!}
	</div>

	{!! Form::label('fecha', 'Fecha Inicio Prod.',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-2">
		{!! Form::text('orden_fecha_inicio', null, ['class' => 'form-control datepicker']) !!}
	</div>

	{!! Form::label('fecha', 'Fecha Compromiso',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-2">
		{!! Form::text('orden_fecha_compromiso', null, ['class' => 'form-control datepicker']) !!}
	</div>
	
</div>
<div class="form-group">

	{!! Form::label('orden_ciudad', 'Ciudad',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-4">
		{!! Form::select('orden_ciudad_id', 
							\Config::get('options.ciudad'),
							null, 
							['class' => 'form-control']) !!}
	</div>

	{!! Form::label('orden_provincia', 'Provincia',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-4">
		{!! Form::select('orden_provincia_id', 
							\Config::get('options.provincia'),
							null, 
							['class' => 'form-control']) !!}
	</div>
	
</div>
<div class="form-group">

	{!! Form::label('producto', 'Productos',
		['class' => 'col-sm-2 control-label']) !!}
	<div class="col-sm-8">
		{!! Form::select('orden_producto_id', 
							$productos,
							null, 
							['class' => 'form-control',
							'id' => 'select_producto']) !!}
	</div>
	<div class="col-sm-2">
		<a class="btn btn-primary" id="add_product">
            <i class="fa fa-plus"></i>
        </a>
	</div>
	
</div>
<div class="form-group">
	<div class="col-sm-9 col-md-offset-2">
		<table id="table-products" cellspacing="0">
	        <thead>
	            <tr>
	            	<th>#</th>
	                <th>Producto</th>
	                <th>Opci&oacute;n</th>
	            </tr>
	        </thead>
	    </table>
	</div>
</div>
@endsection