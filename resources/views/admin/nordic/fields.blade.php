<div class="col-sm-12">
    <div class="col-sm-4">
        <div class="form-group">
            {!! Form::label('orden', 'Orden',
                ['class' => 'control-label']) !!}
            <div class="input-group">
                <input name="orden_id" id="orden_id" type="text" class="form-control" disabled>
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
            {!! Form::text('etiqueta_fecha', null, ['class' => 'form-control datepicker','id'=> 'etiqueta_fecha']) !!}
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
    <div class="col-sm-8">
        <div class="form-group">
            {!! Form::label('producto_detail', 'Detalle Producto',
                ['class' => 'control-label']) !!}
            {!! Form::text('producto_detail', null, ['class' => 'disabled form-control', 'id' => 'producto_detail', 'disabled']) !!}
        </div>
    </div>
</div>