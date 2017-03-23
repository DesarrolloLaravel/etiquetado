<div class="form-group">

	{!! Form::label('trim', 'Trim',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('trim_name', null, ['class' => 'form-control',
								'placeholder' => 'Trim']) !!}
	</div>
	
</div>

{!! Form::hidden('trim_id', null) !!}