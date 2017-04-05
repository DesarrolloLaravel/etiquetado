@extends('app')

@section('htmlheader_title')
    Lotes Anulados
@endsection

@section('contentheader_title')
    Lista de Lotes Anulados
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">
<script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datepicker/locales/bootstrap-datepicker.es.js') }}" type="text/javascript"></script>
<style type="text/css">
    td.details-control {
    background: url("../../img/details_open.png") no-repeat center center;
    cursor: pointer;
    width: 30px;
    }
    tr.shown td.details-control {
        background: url("../../img/details_close.png") no-repeat center center;
        width: 30px;
    }
    .datepicker{z-index:1151 !important;}
</style>
<script type="text/javascript">

    var table;

    $(document).ready(function(){

        $.ajaxSetup({
            headers:{
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".alert").hide();

        table = $('#table-anulados').DataTable({
            "ajax" : "anulados",
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "columnDefs": [{
                "targets": 0,
                "data": null,
                "defaultContent": ""
            }],
            "order": [[ 1, 'desc' ]],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('data-id', aData[1]);
            }
        });    
    });

</script>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <br><br>
                    <a class="btn btn-primary" href="{{ url('/admin/lote') }}" id="activos">
                        Activos
                    </a>
                    <a class="btn btn-default disabled" id="anulados">
                        Anulados
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-anulados" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>N° Gu&iacute;a/Factura</th>
                                <th>Procesador</th>
                                <th>Productor</th>
                                <th>D. Jurada</th>
                                <th>Producci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--@foreach ($anulados as $anulado)
                            <tr role="row" data-id="{{ $anulado->lote_id }}">
                                <td></td>
                                <td>{{ $anulado->lote_id }}</td>
                                <td>{{ $anulado->lote_n_documento }}</td>
                                <td>{{ $anulado->procesador->procesador_name }}</td>
                                <td>{{ $anulado->productor->productor_name }}</td>
                                <td>{{ $anulado->lote_djurada }}</td>
                                <td>{{ $anulado->lote_produccion }}</td>
                            </tr>
                            @endforeach--}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>N° Gu&iacute;a/Factura</th>
                                <th>Procesador</th>
                                <th>Productor</th>
                                <th>D. Jurada</th>
                                <th>Producci&oacute;n</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection