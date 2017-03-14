@section('contentPanel')
  <div class="col-md-5">
    <div class="form-group">
      {!! Form::label('lote', 'LOTES',
        ['class' => 'control-label']) !!}
      {!! Form::select('no_produccion', 
              $no_produccion,
              null, 
              ['size' => '10',
              'class' => 'form-control',
              'id' => 'select_no_produccion']) !!}
    </div>
  </div>
  <div class="col-md-2" style="text-align:center">
    <br><br><br> 
    <div class="form-group">
      <a id="pro_lote" class="btn btn-primary btn-lg">
        <i class="fa fa-angle-double-right"></i>
      </a>
      <hr>
      <a id="np_lote" class="btn btn-primary btn-lg">
        <i class="fa fa-angle-double-left"></i>
      </a>
    </div>
  </div>
  <div class="col-md-5">
    <div class="form-group">
      {!! Form::label('lote_produccion', 'LOTES EN PRODUCCION',
        ['class' => 'control-label']) !!}
      {!! Form::select('produccion', 
              $produccion,
              null, 
              ['size' => '10',
              'class' => 'form-control',
              'id' => 'select_produccion']) !!}
    </div>
  </div>
@endsection