{!! Form::open(['class' => 'form-horizontal',
      'method' => 'POST',
      'id' => 'form-summary']) !!}

    <div class="form-group">
        {!! Form::label('summary', 'N° de Cajas',
        ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-7">
        {!! Form::label('n_cajas', '',
            ['class' => 'control-label', 'id' => 'n_cajas']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('summary', 'Peso Bruto',
        ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-7">
        {!! Form::label('total_peso_bruto', '',
            ['class' => 'control-label', 'id' => 'total_peso_bruto']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('summary', 'Peso Neto',
        ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-7">
        {!! Form::label('total_peso_neto', '',
            ['class' => 'control-label', 'id' => 'total_peso_real']) !!}
        </div>
    </div>

{!! Form::close() !!}

<div style="text-align:center">
    <a id="packing_hoy" class="btn btn-primary">
        Packing Hoy
    </a>
    <a id="packing_actual" class="btn btn-primary">
        Packing Actual
    </a>
    <a id="packing_historico" class="btn btn-primary">
        Packing Historico
    </a>
</div>