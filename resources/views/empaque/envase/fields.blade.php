<div class="form-group">

	{!! Form::label('nombre', 'Nombre',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('envase_nombre', null, ['class' => 'form-control',
								'placeholder' => 'Nombre del Envase']) !!}
	</div>
	
</div>

<div class="form-group">

	{!! Form::label('capacidad', 'Capacidad',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('envase_capacidad', null, ['class' => 'form-control',
								'placeholder' => 'Capacidad del Envase']) !!}
	</div>
	
</div>

{!! Form::hidden('envase_id', null) !!}