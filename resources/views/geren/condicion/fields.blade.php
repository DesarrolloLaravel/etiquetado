<div class="form-group">

	{!! Form::label('condicion', 'Condición',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('condicion_name', null, ['class' => 'form-control',
								'placeholder' => 'Condición']) !!}
	</div>
	
</div>

{!! Form::hidden('condicion_id', null) !!}