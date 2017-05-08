@section('contentPanel')
<div class="col-sm-12">
	<div class="form-group">

		{!! Form::label('numero_despacho', 'Num. Despacho',
			['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::number('despacho_id',$despacho, ['class' => 'form-control','id' =>'num_despacho', 'disabled', 'min' => 0]) !!}
		</div>
		
		{!! Form::label('orden_produccion', 'Orden de Producción',
			['class' => 'col-sm-3 control-label']) !!}
		<div class="input-group col-sm-3">
			<input name="orden_id" id="orden_id" type="text" class="form-control" disabled>
			<span class="input-group-btn">
				<button id="orden_search" class="btn btn-secondary" type="button">
					<i class="fa fa-search"></i>
				</button>
			</span>
		</div>

	</div>
</div>

<div class="col-sm-12">
	<div class="form-group">

		{!! Form::label('fecha', 'Fecha Despacho',
			['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::text('despacho_fecha', $despacho_fecha, ['class' => 'form-control datepicker', 'id' => 'despacho_fecha']) !!}
		</div>

		{!! Form::label('estado', 'Estado',
			['class' => 'col-sm-1 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::select('despacho_estado', 
								$estado,
								null, 
								['class' => 'form-control','id' => 'despacho_estado']) !!}
		</div>

		{!! Form::label('guia', 'Num. Guía',
			['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-2">
			{!! Form::number('despacho_guia', null, 
								['class' => 'form-control','id' => 'despacho_guia','min' => 0]) !!}
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="form-group">
		
		{!! Form::label('etiqueta', 'Etiqueta',
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
			<table id="table-etiqueta" cellspacing="0">
		        <thead>
		            <tr>
		            	<th>#</th>
		                <th>Producto</th>
		                <th>C&oacute;digo</th>
		                <th>Kilos (Kg)</th>
		                <th>Opci&oacute;n</th>
		            </tr>
		        </thead>
		        <tfoot>
		        	<tr>
		        		<th>#</th>
		                <th>Producto</th>
		                <th>C&oacute;digo</th>
		                <th>Kilos (Kg)</th>
		                <th>Opci&oacute;n</th>
		            </tr>
		        </tfoot>
		    </table>
		</div>
	</div>
</div>

@endsection