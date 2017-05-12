<div class="col-sm-12">
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('orden', 'Orden de Trabajo',
                ['class' => 'control-label']) !!}
                <div class="input-group">
                    <input name="orden_id" id="orden_id" type="text" class="form-control"  disabled>
                    <span class="input-group-btn">
                        <button id="orden_search" class="btn btn-secondary" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('etiqueta_lote_id', 'Lote',
                ['class' => 'control-label']) !!}
                <div class="input-group">
                    <input name="lote_id" id="lote_id" type="text" class="form-control" disabled>
                    <span class="input-group-btn">
                        <button id="lote_search" class="btn btn-secondary" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('fecha_etiqueta', 'Fecha Etiqueta',
                ['class' => 'control-label']) !!}
                {!! Form::text('etiqueta_fecha', null, ['class' => 'form-control datepicker']) !!}
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('producto', 'Especie',
                ['class' => 'control-label']) !!}
                {!! Form::text('especie', null ,['class' => 'form-control','id' => 'especie', 'disabled']) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('producto_detail', 'Detalle Producto',
                ['class' => 'control-label']) !!}
                {!! Form::text('producto_detail', null, ['class' => 'disabled form-control', 'id' => 'producto_detail', 'disabled']) !!}
            </div>
        </div>



    </div>
    <div class="col-sm-12">
        <div class="col-sm-2">
            <div class="form-group">
                {!! Form::label('producto', 'Peso Declarado/Neto',
                ['class' => 'control-label']) !!}
                {!! Form::text('peso_real', null, ['class' => 'form-control', 'id' => 'peso_real']) !!}
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                {!! Form::label('glaseado', 'Glaseado',
                ['class' => 'control-label']) !!}
                {!! Form::number('glaseado', 1, ['class' => 'form-control', 'min' => 0, 'max' => 2, 'id' => 'glaseado']) !!}
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                {!! Form::label('producto', 'Peso Estándar',
                ['class' => 'control-label']) !!}
                {!! Form::text('peso_estandar', null, ['class' => 'disabled form-control', 'id' => 'peso_estandar', 'disabled']) !!}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('peso_bruto', 'Peso Bruto',
                ['class' => 'control-label']) !!}
                {!! Form::text('peso_bruto', null, ['class' => 'form-control', 'id' => 'peso_bruto']) !!}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('unidades', 'Unidades',
                ['class' => 'control-label']) !!}
                {!! Form::number('unidades', 0, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('idioma', 'Idioma Etiqueta',
                ['class' => 'control-label']) !!}
                {!! Form::select('idioma',
                \Config::get('options.idioma'),
                null,
                ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('caja_number', 'Número de Caja',
                ['class' => 'control-label']) !!}
                {!! Form::text('caja_number', $proxima_caja, ['class' => 'disabled form-control', 'disabled']) !!}
            </div>
        </div>
        <div class="col-sm-1">
            {!! Form::label('refresh', 'Actualizar',['class' => 'control-label']) !!}
            <a class="btn btn-primary" id="refresh"><i class="fa fa-refresh"></i></a>
        </div>
    </div>