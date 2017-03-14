@extends('app')

@section('htmlheader_title')
    Empresas
@endsection

@section('contentheader_title')
    Empresas
@endsection

@section('main-content')

<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
	var table;
    var frigorifico_id, camara_id, posicion_id;

    $(document).ready(function(){

        $.ajaxSetup({
            headers:{
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".alert").hide();
        $("#form-summary").hide();

        $.get('frigorifico?q=select', function(data){
            $('#select_frigorifico').empty();
            $('#select_frigorifico').append("<option value=''>Todos</option>");
            $.each(data, function(key, element) {
                $('#select_frigorifico').append("<option value='" + key + "'>" + element + "</option>");
            });
        });

        $("#select_frigorifico").click(function(){

            if($("#select_frigorifico").val() == "")
            {
                $('#select_camara').empty();
                $('#select_posicion').empty();
            }
            else
            {
                frigorifico_id = $("#select_frigorifico").val();
                camara_id = undefined;
                posicion_id = undefined;
                $.get('camara?q=select',
                    {frigorifico_id : frigorifico_id},
                    function(data){
                        $('#select_camara').empty();
                        $('#select_posicion').empty();
                        $('#select_camara').append("<option value=''>Todos</option>");
                        $.each(data, function(key, element) {
                            $('#select_camara').append("<option value='" + key + "'>" + element + "</option>");
                        });
                });
            }
        });

        $("#select_camara").click(function(){

            if($("#select_camara").val() == "")
            {
                $('#select_posicion').empty();
            }
            else
            {
                camara_id = $("#select_camara").val();
                posicion_id = undefined;
                $.get('posicion?q=select',
                    {camara_id : camara_id},
                    function(data){
                        console.log(data);
                        $('#select_posicion').empty();
                        $('#select_posicion').append("<option value=''>Todos</option>");
                        $.each(data, function(key, element) {
                            $('#select_posicion').append("<option value='" + key + "'>" + element + "</option>");
                        });
                });
            }
        });

        $("#select_posicion").click(function(){

            posicion_id = $("#select_posicion").val();
        });

        table = $('#table-cajas').DataTable({
            "ajax": {
                "url": "caja?q=search",
                "data": function ( d ) {
                    d.select_lote = $('#select_lote').val();
                    d.select_frigorifico = $('#select_frigorifico').val();
                    d.select_camara = $("#select_camara").val();
                    d.select_posicion = $("#select_posicion").val();
                    // d.custom = $('#myInput').val();
                    // etc
                },
                "dataSrc": function(d){
                    console.log(d.data.length);
                    if(d.data.length > 0)
                    {
                        $("#form-summary").show();
                    }
                    $("#n_cajas").text(d.n_cajas);
                    $("#total_peso_bruto").text(d.total_peso_bruto);
                    $("#total_peso_real").text(d.total_peso_real);
                    return d.data;    
                }
            },
            "deferRender": true,
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "order": [[ 0, 'desc' ]],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('data-id', aData[0]);
            }
        });

        $("#search").click(function(){

            table.ajax.reload();
        });

        $("#packing_hoy").click(function(){
            if($('#select_lote').val() != ""){
                var url = location.href+"/export/today/"+$('#select_lote').val()+'/true';
                window.open(url, '_blank');
            }
            else
                alert("Debes seleccionar un Lote para exportar");
        });

        $("#packing_actual").click(function(){
            if($('#select_lote').val() != ""){
                var url = location.href+"/export/"+$('#select_lote').val();
                window.open(url, '_blank');
            }
            else
                alert("Debes seleccionar un Lote para exportar");
        });

        $("#packing_historico").click(function(){
            if($('#select_lote').val() != ""){
                var url = location.href+"/export/history/"+$('#select_lote').val();
                window.open(url, '_blank');
            }
            else
                alert("Debes seleccionar un Lote para exportar");
        });

    });
</script>
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-header with-border">
                <h3 class="box-title">Buscar Cajas</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-7">
                            @include('admin.stock.search')
                        </div>
                        <div class="col-sm-5">
                            @include('admin.stock.summary')
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-cajas" width="100%">
                        <thead>
                            <tr>
                                <th>N° Caja</th>
                                <th>Lote</th>
                                <th>C&oacute;digo</th>
                                <th>Peso Neto</th>
                                <th>Peso Bruto</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--@foreach ($cajas as $caja)
                            <tr role="row" data-id="{{ $caja->caja_id }}">
                                <td>{{ $caja->caja_id }}</td>
                                <td>{{ $caja->orden_producto->orden->lote->lote_id }}</td>
                                <td>{{ $caja->caja_peso_real }}</td>
                                <td>{{ $caja->caja_peso_bruto }}</td>
                            </tr>
                            @endforeach--}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>N° Caja</th>
                                <th>Lote</th>
                                <th>C&oacute;digo</th>
                                <th>Peso Neto</th>
                                <th>Peso Bruto</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection