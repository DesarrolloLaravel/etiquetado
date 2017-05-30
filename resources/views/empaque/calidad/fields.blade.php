<div class="form-group">

	{!! Form::label('nombre', 'Nombre',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('calidad_nombre', null, ['class' => 'form-control',
								'placeholder' => 'Nombre de Calidad']) !!}
	</div>
	
</div>

{!! Form::hidden('calidad_id', null) !!}