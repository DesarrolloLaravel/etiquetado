@section('contentPanel')
<div class="col-sm-12">
	<div class="col-sm-4">
		<div class="form-group">
			{!! Form::label('especie', 'Numero de Pallet',
			['class' => 'control-label']) !!}
			{!! Form::text('especie', $proximo_pallet, ['class' => 'form-control','id'=>'especie','disabled']) !!}
		</div>
	</div>
	<div class="form-group">


		<div class="col-sm-4">
			
			{!! Form::label('fecha_etiqueta', 'Fecha Etiqueta',
			['class' => 'control-label']) !!}
			{!! Form::text('etiqueta_fecha', null, ['class' => 'form-control datepicker']) !!}
			
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="form-group">
		
		{!! Form::label('etiqueta', 'Etiqueta Caja',
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