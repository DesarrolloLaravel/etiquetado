<div class="form-group">

	{!! Form::label('nombre', 'Nombre',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('users_name', null, ['class' => 'form-control',
								'placeholder' => 'Nombre Completo']) !!}
	</div>
	
</div>
<div class="form-group">

	{!! Form::label('nombre', 'Email',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::email('users_email', null, ['class' => 'form-control',
								'placeholder' => 'Email']) !!}
	</div>
	
</div>
<div class="form-group">

	{!! Form::label('nombre', 'Rol',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::select('users_role',
							\Config::get('options.role'),
							null, 
							['class' => 'form-control']) !!}
	</div>
	
</div>
<div class="form-group">

	{!! Form::label('nombre', 'ID-Usuario',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::text('users_user', null, ['class' => 'form-control',
								'placeholder' => 'Nombre de Usuario']) !!}
	</div>
	
</div>
<div class="form-group">

	{!! Form::label('nombre', 'Clave',
		['class' => 'col-sm-3 control-label']) !!}
	<div class="col-sm-7">
		{!! Form::password('password', ['class' => 'form-control',
								'placeholder' => '*******']) !!}
	</div>
	
</div>
{!! Form::hidden('users_id', null) !!}