<div class="form-group">

	{!! Form::label('nombre', 'Nombre',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('productor_name', null, ['class' => 'form-control',
								'placeholder' => 'Nombre del Productor']) !!}
	</div>
	
</div>

{!! Form::hidden('productor_id', null) !!}