@extends('app')

@section('htmlheader_title')
    Etiqueta - Nordic
@endsection

@section('contentheader_title')
    Imprimir Etiqueta - Nordic
@endsection

@section('main-content')
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">
    <script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datepicker/locales/bootstrap-datepicker.es.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/jquery.dataTables.min.css') }}">
    <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">

        var table, table2;
        var lote_id;

        $(document).ready(function(){

            $('.alert').hide();
            alert = new Alert('#notifications');

            $('.datepicker').datepicker({
                format : 'dd-mm-yyyy',
                autoclose: true,
                language : 'es'
            });

            $('.datepicker').datepicker('update', new Date());

            $("#refresh").click(function(){
                if($("#lote_id").val() == "")
                    alert.error("Debes elegir un lote");
                else
                {
                    $.get("../lote/show",
                            {lote_id : lote_id},
                            function(data){
                                $("#caja_number").val(data.caja_number);
                            }).fail(function(resp){
                        alert.error("Ha ocurrido un error. Inténtalo más tarde.");
                    });
                }
            });

            $("#print").click(function(){

                var form = $("#form-add");
                //obtengo url
                var url = form.attr('action');

                var disabled = form.find(':input:disabled').removeAttr('disabled');
                //obtengo la informacion del formulario
                var data = form.serialize();

                disabled.attr('disabled','disabled');

                $.post(url, data,
                        function(data){
                            if(data['estado'] == "ok")
                                var printPage = window.open('{{ url("admin/nordic/print") }}'+'/'+data['orden_id']+'/'+data['producto_id']+'/'+data['fecha'], '');
                            else
                                alert.error("Ha ocurrido un error. Inténtalo más tarde.");

                        }).fail(function(resp){

                    html = "";
                    for(var key in resp.responseJSON)
                    {
                        html += resp.responseJSON[key][0] + "<br>";
                    }
                    alert.error(html);
                });
            });

            $("#lote_search").click(function(){

                if(table != undefined)
                {
                    table.destroy();
                }

                table = $('#table-lotes').DataTable({
                    "ajax" : "../lote?q=etiqueta",
                    "language": {
                        "url": "../../plugins/datatables/es_ES.txt"
                    },
                    "order": [[ 1, 'desc' ]],
                    "columnDefs": [
                        { "visible": false, "targets": 0 }
                    ],
                    'fnCreatedRow': function (nRow, aData, iDataIndex) {
                        $(nRow).attr('data-id', aData[1]);
                    }
                });

                $("#modal_lote").modal('show');
            });

            $("#orden_search").click(function(){

                if(lote_id == undefined)
                    alert.error("Debes seleccionar un LOTE");
                else
                {
                    if(table2 != undefined)
                        table2.destroy();

                    table2 = $('#table-ordenes').DataTable({
                        "ajax" : "../ordenproduccion?q=etiqueta&lote_id="+lote_id,
                        "language": {
                            "url": "../../plugins/datatables/es_ES.txt"
                        },
                        "order": [[ 1, 'desc' ]],
                        "columnDefs": [
                            { "visible": false, "targets": 0 }
                        ],
                        'fnCreatedRow': function (nRow, aData, iDataIndex) {
                            $(nRow).attr('data-id', aData[1]);
                        }
                    });

                    $("#modal_orden").modal('show');
                }

            });

            $('#table-ordenes tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } );

            $('#table-lotes tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } );

            $("#select_orden").click(function(){

                if(table2.rows('tr.selected').data().length > 0)
                {
                    orden_id = $("#table-ordenes").find('tbody tr.selected').data('id');

                    $.get("../ordenproduccion/show?q=etiqueta",
                            {orden_id : orden_id},
                            function(data){

                                console.log(data.orden_productos);
                                var html_select = '';
                                for (var key in data.orden_productos){
                                    html_select += '<option value="'+data.orden_productos[key]+'">'+
                                            key+
                                            '</option>';
                                }
                                $("#select_productos").empty();
                                $("#select_productos").append(html_select);

                                $('.select2').select2().select2("val", null);

                                $("#orden_detail").val(data.orden_descripcion);
                                $("#orden_id").val(data.orden_id);

                                $("#modal_orden").modal('hide');

                            });
                }
                else
                    alert.error("Debes seleccionar una ORDEN");
            });

            $('#select_lote').click( function () {

                if(table.rows('tr.selected').data().length > 0)
                {
                    lote_id = $("#table-lotes").find('tbody tr.selected').data('id');

                    $.get("../lote/show",
                            {lote_id : lote_id},
                            function(data){

                                $("#modal_lote").modal('hide');
                                $("#lote_id").val(data.lote_id);
                                $("#caja_number").val(data.caja_number);
                                $("#orden_detail").val("");
                                $("#orden_id").val("");
                                $("#select_productos").html("");
                                $("#producto_detail").val("");

                            });
                }
                else
                    alert.error("Debes seleccionar un LOTE");
            });

            $("#select_productos").on("select2:select", function(){

                if($("#select_productos").select2('val') != "")
                {
                    producto_id = $("#select_productos").select2('val');

                    $.get('../producto/show',
                            {producto_id : producto_id},
                            function(data){
                                $("#producto_detail").val(data.producto_fullname);
                            });
                }

            });

        });

    </script>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">NORDIC - Ingreso de datos</h3>
                </div>
                <div class="box-body">
                    {!! Form::open(['url' => 'admin/nordic/store',
                      'method' => 'POST',
                      'id' => 'form-add']) !!}

                    @include('admin.nordic.fields')

                    {!! Form::close() !!}

                </div>
                <div class="box-footer" style="text-align:center">
                    <a id="print" class="btn btn-lg btn-primary">Imprimir</a>
                </div>
            </div>
        </div>
    </div>

    @include('admin.etiqueta.modallotes')
    @include('admin.etiqueta.modalordenproduccion')

@endsection