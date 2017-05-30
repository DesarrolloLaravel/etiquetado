<div class="form-group">

	{!! Form::label('nombre', 'Nombre',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('formato_nombre', null, ['class' => 'form-control',
								'placeholder' => 'Nombre del Formato']) !!}
	</div>
	
</div>

<div class="form-group">

	{!! Form::label('rut', 'Abreviatura',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('formato_abreviatura', null, ['class' => 'form-control',
								'placeholder' => 'Abreviatura']) !!}
	</div>
	
</div>

{!! Form::hidden('formato_id', null) !!}