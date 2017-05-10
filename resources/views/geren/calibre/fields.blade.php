<div class="form-group">
    {!! Form::label('nombre', 'Nombre',
        ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::text('calibre_nombre', null, ['class' => 'form-control',
                                'placeholder' => 'Calibre']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('unidad_medida', 'Unidad de Medida',
        ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::select('calibre_unidad_medida_id',
							$unidades,
							null, 
							['class' => 'form-control']) !!}
    </div>
</div>
{!! Form::hidden('calibre_id', null) !!}