<div class="form-group">

	{!! Form::label('variante', 'Variante Secundaria',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('varianteDos_name', null, ['class' => 'form-control',
								'placeholder' => 'Variante Secundaria']) !!}
	</div>
	
</div>

{!! Form::hidden('varianteDos_id', null) !!}