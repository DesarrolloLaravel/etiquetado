@section('contentPanel')
<div class="col-sm-12">
	<div class="form-group">

		{!! Form::label('nombre', 'Num. Orden',
				['class' => 'col-sm-1 control-label']) !!}
			<div class="col-sm-3">
				{!! Form::number('orden_number', $proxima_orden, ['class' => 'form-control', 'id' => 'orden_id', 'min' => 0, 'disabled']) !!}
			</div>

		{!! Form::label('descripcion', 'Descripción',
			['class' => 'col-sm-1 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::text('orden_descripcion', null, ['class' => 'form-control']) !!}
		</div>

		{!! Form::label('orden_cliente', 'Cliente',
			['class' => 'col-sm-1 control-label']) !!}
			<div class="col-sm-3">
				{!! Form::select('orden_cliente_id', $clientes,null, ['class' => 'form-control']) !!}
			</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="form-group">

		{!! Form::label('fecha', 'Fecha Orden',
			['class' => 'col-sm-1 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::text('orden_fecha', $orden_fecha, ['class' => 'form-control datepicker']) !!}
		</div>

		{!! Form::label('fecha', 'Fecha Inicio Prod.',
			['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::text('orden_fecha_inicio', $orden_fecha_inicio, ['class' => 'form-control datepicker']) !!}
		</div>

		{!! Form::label('fecha', 'Fecha Compromiso',
			['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::text('orden_fecha_compromiso', $orden_fecha_compromiso, ['class' => 'form-control datepicker']) !!}
		</div>
		
	</div>
</div>


<div class="col-sm-12">
	<div class="form-group">

		{!! Form::label('especie', 'Especie',
			['class' => 'col-sm-1 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::select('orden_especie_id', 
								$especies,
								null, 
								['class' => 'form-control','id' => 'especie_id']) !!}
		</div>

		{!! Form::label('producto', 'Productos',
			['class' => 'col-sm-1 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::select('orden_producto_id',$productos, 
								null, 
								['class' => 'form-control','id' => 'producto_ide']) !!}
		</div>

		{!! Form::label('kilos', 'Kilos Declarados',
				['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::number('orden_kilos_declarados', null, ['class' => 'form-control', 'min' => 0]) !!}	
		</div>

		<div class="col-sm-2">
			<a class="btn btn-primary" id="add_product">
	            <i class="fa fa-plus"></i>
	        </a>
		</div>
		
	</div>
</div>

<div class="col-sm-12">
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
</div>

@endsection