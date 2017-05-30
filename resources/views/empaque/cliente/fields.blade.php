<div class="form-group">

	{!! Form::label('nombre', 'Nombre',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('cliente_nombre', null, ['class' => 'form-control',
								'placeholder' => 'Nombre del Cliente']) !!}
	</div>
	
</div>

{!! Form::hidden('cliente_id', null) !!}