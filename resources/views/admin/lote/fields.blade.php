@section('contentPanel')
<div class="col-sm-6">
	<div class="col-sm-3">
		<div class="form-group">
			{!! Form::label('year', 'Año',
				['class' => 'control-label']) !!}
			{!! Form::selectYear('lote_year', 
				\Carbon\Carbon::now()->year, 
				\Carbon\Carbon::now()->subYears(5)->year,
				\Carbon\Carbon::now()->year, 
				['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			{!! Form::label('lote', 'Lote',
				['class' => 'control-label']) !!}
			{!! Form::number('lote_number', $proximo_lote, ['class' => 'form-control', 'disabled']) !!}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('lote_tipo', 'Tipo de Lote',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_tipo_id',
							\Config::get('options.lote_tipo'),
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('lote_n_documento', 'N° Guía/Factura',
				['class' => 'control-label']) !!}
			{!! Form::text('lote_n_documento', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('lote_djurada', 'Declaración Jurada',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_djurada', 
							\Config::get('options.djurada'),
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
	<!-- 2nd row -->
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('productor', 'Productor',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_productor_id',
							$productores,
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('elaborador', 'Elaborador',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_elaborador_id', 
							$elaboradores,
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
	<!-- 3rd row-->
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('procesadora', 'Procesador',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_procesador_id', 
							$procesadores,
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('procesadora', 'Calidad de MP',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_calidad_id', 
							$calidades,
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
	<!--4th row -->
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('especie', 'Especie',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_especie_id', 
							$especies,
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<div class="form-group">
				{!! Form::label('lote_condicion', 'Condición de MP',
					['class' => 'control-label']) !!}
				{!! Form::select('lote_condicion', 
								$condiciones,
								null, 
								['class' => 'form-control']) !!}
			</div>
		</div>
	</div>
	<!-- 5th row-->
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('tipo_mp', 'Formato MP',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_mp_id', 
							$formatos,
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			{!! Form::label('destino', 'Destino Preliminar',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_destino_id', 
							$destinos,
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
</div>
<div class="col-sm-6">
	<div class="col-sm-4">
		<div class="form-group">
			{!! Form::label('fecha_ingreso', 'Fecha Guía/Factura',
				['class' => 'control-label']) !!}
			{!! Form::text('lote_fecha_documento', $fecha_documento, ['class' => 'form-control datepicker']) !!}
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			{!! Form::label('nombre', 'Fecha Ingreso Planta',
				['class' => 'control-label']) !!}
			{!! Form::text('lote_fecha_planta', $fecha_planta, ['class' => 'form-control datepicker']) !!}
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			{!! Form::label('fecha_expiracion', 'Fecha Vencimiento',
				['class' => 'control-label']) !!}
			{!! Form::text('lote_fecha_expiracion', $fecha_expiracion, ['class' => 'form-control datepicker']) !!}
		</div>
	</div>
	<!-- 2nd row -->
	
</div>
<hr>
<div class="col-sm-6">
	<div class="col-xs-6">
		<div class="form-group">
			{!! Form::label('kilos', 'Kilos Declarados',
				['class' => 'control-label']) !!}
			{!! Form::number('lote_kilos_declarado', null, ['class' => 'form-control', 'min' => 0]) !!}
		</div>
	</div>
	<div class="col-xs-6">
		<div class="form-group">
			{!! Form::label('kilos', 'Cajas Declarados',
				['class' => 'control-label']) !!}
			{!! Form::number('lote_cajas_declarado', null, ['class' => 'form-control', 'min' => 0]) !!}
		</div>
	</div>
</div>
<div class="col-sm-6">
	<div class="col-xs-6">
		<div class="form-group">
			{!! Form::label('cliente', 'Cliente',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_cliente_id', $clientes, null , ['class' => 'form-control']) !!}
		</div>
	</div>
</div>

<div class="col-sm-6">
	<div class="col-xs-4">
		<div class="form-group">
			{!! Form::label('reestricciones', 'Restricciones',
				['class' => 'control-label']) !!}
			{!! Form::select('lote_reestriccion', 
							\Config::get('options.reestriccion'),
							null, 
							['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-xs-8">
		<div class="form-group">
			{!! Form::label('kilos', 'Observaciones',
				['class' => 'control-label']) !!}
			{!! Form::textarea('lote_observaciones', null, ['class'=>'form-control','rows' => 5]) !!}
		</div>
	</div>
</div>
{!! Form::hidden('lote_id', null) !!}
@endsection