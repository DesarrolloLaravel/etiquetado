@extends('app')

@section('htmlheader_title')
    Despachados
@endsection

@section('contentheader_title')
    Despachados
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">
<script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datepicker/locales/bootstrap-datepicker.es.js') }}" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/jquery.dataTables.min.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<style type="text/css">
    td.details-control {
    background: url("../../img/details_open.png") no-repeat center center;
    cursor: pointer;
    width: 50px;
    }
    tr.shown td.details-control {
        background: url("../../img/details_close.png") no-repeat center center;
        width: 50px;
    }
    .datepicker{z-index:1151 !important;}
</style>
<script type="text/javascript">
    
    var table;

    $.ajaxSetup({
        headers:{
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){

        orden_id = $("#orden_id").val();
        $(".alert").hide();

        table = $('#table-despacho').DataTable({
            "ajax" : "../despacho?q=despachado",
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "order": [[ 0, 'desc' ]],
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-xs btn-success' id='excel'><i class='fa fa-file-excel-o'></i></button>"
            }],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('data-id', aData[0]);
            }
        });

        $('#table-despacho tbody').on( 'click', '#excel', function ()
        {
            $(".alert").hide();

            var despacho_id = $(this).parents('tr').data('id');

            alert(despacho_id);
            var url = location.href+"../../imprimir_informe/"+despacho_id;
            window.open(url, '_blank');

        });

    });

</script>
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" href="{{ url('/almacenamiento/despacho') }}" id="despachos">
                        Despachos
                    </a>
                    <a class="btn btn-default disabled"  id="despachados">
                        Despachados
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-despacho" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estado</th>
                                <th>Orden de Producci&oacute;n</th>
                                <th>Nun. Gu&iacute;a</th>
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Estado</th>
                                <th>Orden de Producci&oacute;n</th>
                                <th>Nun. Gu&iacute;a</th>
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection