@section('contentPanel')
<div class="col-sm-12">
	<div class="form-group">

		{!! Form::label('nombre', 'Num. Orden',
				['class' => 'col-sm-2 control-label']) !!}
			<div class="col-sm-2">
				{!! Form::number('orden_number', $proxima_orden, ['class' => 'form-control', 'id' => 'orden_trabajo_id', 'min' => 0, 'disabled']) !!}
			</div>

		{!! Form::label('orden_produccion', 'Num. Orden Producción',
			['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::select('orden_trabajo_orden_produccion',$ordenProduccion, null, ['class' => 'form-control','id' => 'orden_prod']) !!}
		</div>

		{!! Form::label('fecha', 'Fecha Orden Trabajo',
			['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::text('orden_fecha', $orden_trabajo_fecha, ['class' => 'form-control datepicker', 'id' => 'orden_fecha']) !!}
		</div>
	</div>
</div>

<div class="col-sm-12">
	<div class="form-group">

		{!! Form::label('especie', 'Especie',
			['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('orden_trabajo_especie', 
								$especies,
								null, 
								['class' => 'form-control','id' => 'especie_id']) !!}
		</div>

		{!! Form::label('producto', 'Productos',
			['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('orden_trabajo_producto',$productos, 
								null, 
								['class' => 'form-control','id' => 'producto_ide']) !!}
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="form-group">
		
		{!! Form::label('etiqueta', 'Etiqueta Pallet',
			['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-6">
			{!! Form::text('etiqueta_pallet', null, ['class' => 'form-control','id' => 'etiqueta_ide']) !!}
		</div>

		<div class="col-sm-2">
			<a class="btn btn-primary" id="add_pallet">
	            <i class="fa fa-plus"></i>
	        </a>
		</div>
	</div>
</div>

<div class="col-sm-12">
	<div class="form-group">
		<div class="col-sm-12">
			<table id="table-pallet" cellspacing="0">
		        <thead>
		            <tr>
		            	<th></th>
		                <th>Número Lote</th>
		                <th>Pallet</th>
		                <th>Peso (KG)</th>
		                <th>Opci&oacute;n</th>
		            </tr>
		        </thead>
		        <tfoot>
		        	<tr>
		        		<th></th>
		                <th></th>
		                <th>Peso Total (KG)</th>
		                <th></th>
		                <th></th>
		            </tr>
		        </tfoot>
		    </table>
		</div>
	</div>
</div>

@endsection