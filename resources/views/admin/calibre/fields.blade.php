<div class="form-group">
    {!! Form::label('nombre', 'Nombre',
        ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::text('calibre_nombre', null, ['class' => 'form-control',
                                'placeholder' => 'Calibre']) !!}
    </div>
</div>

{!! Form::hidden('calibre_id', null) !!}