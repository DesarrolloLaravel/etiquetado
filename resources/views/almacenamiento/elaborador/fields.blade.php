<div class="form-group">

	{!! Form::label('nombre', 'Nombre',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('elaborador_name', null, ['class' => 'form-control',
								'placeholder' => 'Nombre del Elaborador']) !!}
	</div>
	
</div>

<div class="form-group">

	{!! Form::label('rut', 'RUT',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('elaborador_rut', null, ['class' => 'form-control',
								'placeholder' => 'RUT']) !!}
	</div>
	
</div>

{!! Form::hidden('elaborador_id', null) !!}