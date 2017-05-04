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


    <div class="modal-footer" style="text-align:center">
        <a id="search" class="btn btn-primary">
            <i class="fa fa-search"></i>
        </a>
    </div>
{!! Form::close() !!}