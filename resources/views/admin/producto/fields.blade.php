<div class="col-sm-12">
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre',
            ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::text('producto_nombre', null, ['class' => 'form-control',
                                    'placeholder' => 'Nombre del Producto']) !!}
		</div>
		{!! Form::label('producto_codigo', 'Codigo',
            ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::text('producto_codigo', null, ['class' => 'form-control',
                                    'placeholder' => 'Código']) !!}
		</div>
	</div>
</div>

<div class="col-sm-12">
	<div class="form-group">
		{!! Form::label('nombre', 'Peso',
            ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::text('producto_peso', null, ['class' => 'form-control',
                                    'placeholder' => 'Peso del Producto']) !!}
		</div>
		{!! Form::label('producto_especie', 'Especie',
            ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_especie',
                                $especies,
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
	</div>
</div>

<div class="col-sm-12">
	<div class="form-group">
		{!! Form::label('producto_conservacion', 'Condición',
		['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_condicion',
                                \Config::get('producto.condicion'),
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
		{!! Form::label('producto_formato', 'Formato',
		['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_formato',
                                $formatos,
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="form-group">
		{!! Form::label('producto_trim', 'Trim',
            ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_trim',
                                $trims,
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
		{!! Form::label('producto_calidad', 'Calidad',
		['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_calidad',
                                $calidades,
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
	</div>
</div>

<div class="col-sm-12">
	<div class="form-group">
		{!! Form::label('producto_variante', 'Variante',
            ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_variante',
                                \Config::get('producto.variante'),
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
		{!! Form::label('producto_calibre', 'Calibre',
		['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_calibre',
                                $calibres,
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
	</div>
</div>

<div class="col-sm-12">
	<div class="form-group">
		{!! Form::label('producto_v2', 'V2',
            ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_v2',
                                \Config::get('producto.v2'),
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
		{!! Form::label('producto_envase1', 'Envase 1',
		['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_envase1',
                                $envases,
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
	</div>
</div>


<div class="col-sm-12">
	<div class="form-group">
		{!! Form::label('producto_envase2', 'Envase 2',
            ['class' => 'col-sm-2 control-label']) !!}
		<div class="col-sm-4">
			{!! Form::select('producto_envase2',
                                \Config::get('producto.env2'),
                                null,
                                ['class' => 'form-control select2',
                                'style' => 'width:100%']) !!}
		</div>
	</div>
</div>

{!! Form::hidden('producto_id', null) !!}