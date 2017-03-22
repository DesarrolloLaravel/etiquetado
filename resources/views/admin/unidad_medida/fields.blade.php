<div class="form-group">

	{!! Form::label('nombre', 'Nombre',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('unidad_medida_nombre', null, ['class' => 'form-control',
								'placeholder' => 'Nombre de Unidad de Medida']) !!}
	</div>
	
</div>

<div class="form-group">

	{!! Form::label('nombre_comercial', 'Abreviacion',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('unidad_medida_abreviacion', null, ['class' => 'form-control',
								'placeholder' => 'Abreviacion Unidad de Medida ']) !!}
	</div>
	
</div>


{!! Form::hidden('unidad_medida_id', null) !!}