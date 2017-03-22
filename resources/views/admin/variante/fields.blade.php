<div class="form-group">

	{!! Form::label('variante', 'Variante',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('variante_name', null, ['class' => 'form-control',
								'placeholder' => 'Variante']) !!}
	</div>
	
</div>

{!! Form::hidden('variante_id', null) !!}