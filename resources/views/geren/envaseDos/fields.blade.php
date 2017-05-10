<div class="form-group">

	{!! Form::label('nombre', 'Nombre',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('envaseDos_nombre', null, ['class' => 'form-control',
								'placeholder' => 'Nombre del Envase']) !!}
	</div>
	
</div>

<div class="form-group">

	{!! Form::label('capacidad', 'Capacidad',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('envaseDos_capacidad', null, ['class' => 'form-control',
								'placeholder' => 'Capacidad del Envase']) !!}
	</div>
	
</div>

{!! Form::hidden('envaseDos_id', null) !!}