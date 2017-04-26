@extends('app')

@section('htmlheader_title')
    Orden de Trabajo
@endsection

@section('contentheader_title')
    Lista Ordenes de Trabajo
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

    function format ( dataSource ) {
        var html = '<h4>Información</h4>\
                    <table class="table display" cellpadding="0" cellspacing="0" border="0">';
        c = 0;
        for (var key in dataSource){
            if(c == 0)
            {
                html += '<tr>'+
                        '<td width="25%">' + key             +'</td>'+
                       '<td width="25%" style="border-right: 1px solid #ebebeb;">' + dataSource[key] +'</td>';
                c++;
            }
            else
            {
                html += '<td width="25%">' + key             +'</td>'+
                       '<td width="25%" style="border-right: 1px solid #ebebeb;">' + dataSource[key] +'</td>'+
                        '</tr>';
                c = 0;
            }
        }        
        return html += '</table>';  
    }

    var table;
    var table_pallet;
    var arr_products = [];
   
    $(document).ready(function(){

        $('.select2').select2();

        $.ajaxSetup({
            headers:{
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    	$(".alert").hide();

    	table = $('#table-trabajos').DataTable({
            "ajax" : "ordentrabajo",
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "order": [[ 1, 'desc' ]],
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-xs btn-primary' id='edit'><i class='fa fa-pencil'></i></button>\
                    <button class='btn btn-xs btn-danger' id='delete'><i class='fa fa-close'></i></button>"
            }],

            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).find('td:first').addClass('details-control');
                $(nRow).attr('data-id', aData[1]);
            }
        });
    
        $("#add").click(function(){

            arr_products = [];

            if(table_pallet != undefined)
            {
                table_pallet.destroy();
            }

            $.get('ordentrabajo/create',function(data){

                $('#modal_add .modal-dialog .modal-content .modal-body').find('#form-add').html(data['section']);
                $('#modal_add').modal('show');

                $('.select2').select2();

                table_pallet = $('#table-pallet').DataTable({
                    "language": {
                        "url": "../../plugins/datatables/es_ES.txt"
                    },
                    "order": [[ 1, 'desc' ]],
                    "columnDefs": [{
                        "targets": -1,
                        "data": null,
                        "defaultContent": "<a class='btn btn-xs btn-danger' id='delete_product'><i class='fa fa-close'></i></a>"
                    }],
                    'fnCreatedRow': function (nRow, aData, iDataIndex) {
                        $(nRow).attr('data-id', aData[0]);
                    }
                });
            }); 
        });

    });

</script>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i>
                        Agregar Orden de Trabajo
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-trabajos" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>Orden de Producci&oacute;n</th>
                                <th>Especie</th>
                                <th>Producto</th>
                                <th>Peso (Kg)</th>
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>Orden de Producci&oacute;n</th>
                                <th>Especie</th>
                                <th>Producto</th>
                                <th>Peso (Kg)</th>
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@include('admin.orden_trabajo.modaladd')
@include('admin.orden_trabajo.modaledit')

@endsection