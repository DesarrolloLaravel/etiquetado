<div class="form-group">
    {!! Form::label('nombre', 'Nombre Comercial',
        ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::text('especie_comercial_name', null, ['class' => 'form-control',
                                'placeholder' => 'Nombre Comercial']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('nombre', 'Nombre',
        ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::text('especie_name', null, ['class' => 'form-control',
                                'placeholder' => 'Nombre']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('nombre', 'Abreviatura',
        ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::text('especie_abbreviation', null, ['class' => 'form-control',
                                'placeholder' => 'Abreviatura']) !!}
    </div>
</div>

{!! Form::hidden('especie_id', null) !!}