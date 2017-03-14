@extends('app')

@section('htmlheader_title')
    Lotes
@endsection

@section('contentheader_title')
    Lista de Lotes
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
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

    $(document).ready(function(){

    	$.ajaxSetup({
	        headers:{
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

        $(".alert").hide();

        table = $('#table-lotes').DataTable({
            "ajax" : "lote",
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "order": [[ 1, 'desc' ]],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).find('td:first').addClass('details-control');
                $(nRow).attr('data-id', aData[1]);
            }
        });

        $('#table-lotes tbody').on('click', 'td.details-control', function () {

            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } 
            else {
                // Open this row
                lote_id = $(this).parents('tr').data('id');
                $.get("lote/show",
                    {lote_id : lote_id},
                    function(data)
                    {
                        var arr = [];
                        arr['Fecha Guía/Factura'] = data.lote_fecha_documento;
                        arr['Fecha Ingreso Planta'] = data.lote_fecha_planta;
                        arr['Kilos Recibidos'] = data.lote_kilos_recepcion;
                        arr['Kilos Declarados'] = data.lote_kilos_declarado;
                        arr['Cajas Recibidas'] = data.lote_cajas_recepcion;
                        arr['Cajas Declaradas'] = data.lote_cajas_declarado;
                        arr['Declaración Jurada'] = data.lote_djurada;
                        arr['Reestricción'] = data.lote_reestriccion;
                        arr['Observaciones'] = data.lote_observaciones;

                        row.child([format(arr)]).show();
                        tr.addClass('shown');

                    }).fail(function()
                    {
                        alert("Ocurrió un error. Inténtalo más tarde.");
                    });
            }
        });

    });

</script>
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-lotes" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>T. Lote</th>
                                <th>N° Gu&iacute;a/Factura</th>
                                <th>Procesador</th>
                                <th>Productor</th>
                                <th>D. Jurada</th>
                                <th>Producci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lotes as $lote)
                            <tr role="row" data-id="{{ $lote->lote_id }}">
                                <td></td>
                                <td>{{ $lote->lote_id }}</td>
                                <td>{{ $lote->lote_tipo }}</td>
                                <td>{{ $lote->lote_n_documento }}</td>
                                <td>{{ $lote->procesador->procesador_name }}</td>
                                <td>{{ $lote->productor->productor_name }}</td>
                                <td>{{ $lote->lote_djurada }}</td>
                                <td>{{ $lote->lote_produccion }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>T. Lote</th>
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
</div>

@endsection