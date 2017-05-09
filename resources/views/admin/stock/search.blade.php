{!! Form::open(['url' => 'admin/caja?q=search',
      'class' => 'form-horizontal',
      'method' => 'POST',
      'id' => 'form-search']) !!}

    <div class="form-group">
        {!! Form::label('lote', 'Lote',
        ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-7">
        {!! Form::select('select_lote', $lotes, null,['class' => 'form-control','id' => 'select_lote']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('frigorifico', 'Frigorifico',
        ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-7">
        {!! Form::select('select_frigorifico', [], null,['class' => 'form-control','id' => 'select_frigorifico']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('camara', 'Camara',
        ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-7">
        {!! Form::select('select_camara', [], null,['class' => 'form-control','id' => 'select_camara']) !!}
        </div>
    </div>

    <!-- <div class="form-group">
        {!! Form::label('camara', 'Tipo*',
        ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-7">
        {!! Form::select('select_tipo', [1=>'Ambos',2=>'Producto Terminado',3=>'Materia Prima'], null,['class' => 'form-control','id' => 'select_tipo']) !!}
        </div>
    </div>
    <p>* No afecta en la busqueda de cajas, solo en los reportes.</p> -->


    <div class="modal-footer" style="text-align:center">
        <a id="search" class="btn btn-primary">
            <i class="fa fa-search"></i>
        </a>
    </div>
{!! Form::close() !!}