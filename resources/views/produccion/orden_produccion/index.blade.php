@extends('app')

@section('htmlheader_title')
    Orden de Producci&oacute;n
@endsection

@section('contentheader_title')
    Lista Ordenes de Producci&oacute;n
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
    var t;
    var arr_products = [];

    $(document).ready(function(){

        $.ajaxSetup({
            headers:{
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    	$(".alert").hide();

    	table = $('#table-ordenes').DataTable({
            "ajax" : "ordenproduccion",
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

        $('#table-ordenes tbody').on('click', 'td.details-control', function () {

            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } 
            else {
                // Open this row
                orden_id = $(this).parents('tr').data('id');
                $.get("ordenproduccion/show",
                    {orden_id : orden_id},
                    function(data)
                    {
                        var arr = [];
                        arr['Lote'] = data.orden_lote_id;
                        arr['Descripción'] = data.orden_descripcion;
                        var i = 0;
                        for (var key in data.orden_productos) {
                            arr['Producto '+(i+1)] = data.orden_productos[key];
                            i++;
                        };
                        row.child([format(arr)]).show();
                        tr.addClass('shown');

                    }).fail(function()
                    {
                        alert("Ocurrió un error. Inténtalo más tarde.");
                    });
            }
        });

        $("#add").click(function(){

            $.get('ordenproduccion/create',
                function(data){

                    $('#modal_add .modal-dialog .modal-content .modal-body').find('#form-add').html(data[1]);
                    $('#modal_add').modal('show');

                    t = $('#table-products').DataTable({
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

                    $('#table-products tbody').on('click', '#delete_product', function () {
                        product_id = $(this).parents('tr').data('id');

                        arr_products.splice(arr_products.indexOf(product_id));

                        t.row( $(this).parents('tr') )
                            .remove()
                            .draw();
                    });

                    $('.datepicker').datepicker({
                        format : 'dd-mm-yyyy',
                        autoclose: true,
                        language : 'es'
                    });

                    $('.datepicker').datepicker('update', new Date());
                });
        });

        $(document).on('click','#save',function(event){

            var form = $("#form-add");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            var data = form.serialize()+ '&productos=' + arr_products;

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El registro fue guardado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-add').trigger("reset");

                    $('#modal_add').modal('hide');
                    
                    t = '';
                    arr_products = [];

                    table.ajax.reload();
                }

            }).fail(function(resp){
                $('#modal_add').animate({ scrollTop: 0 }, 'slow');
                var html = "";
                for(var key in resp.responseJSON)
                {
                    html += resp.responseJSON[key][0] + "<br>";
                }
                $(".alert-success").hide();
                $(".alert-danger").html(html).show();
            });
        });

        $(document).on('click','#add_product',function(event){

            product_id = $("#select_producto").val();
            if(product_id != '')
            {
                in_array = false;
                
                if(arr_products.indexOf(product_id) >= 0)
                {
                    in_array = true;
                }

                if(in_array)
                {
                    alert("El producto ya fue agregado a la lista.")
                }
                else
                {
                    t.row.add( [
                        product_id,
                        $("#select_producto option:selected").text()
                    ] ).draw( false );
                    arr_products.push(product_id);
                }
            }
            else
            {
            alert("Debes seleccionar un PRODUCTO");
            }
        });

    });

</script>
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i>
                        Agregar Orden de Producci&oacute;n
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-ordenes" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>N° Lote</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordenes as $orden)
                            <tr role="row" data-id="{{ $orden->orden_id }}">
                                <td class="details-control"></td>
                                <td>{{ $orden->orden_id }}</td>
                                <td>{{ $orden->lote->lote_id }}</td>
                                <td>{{ \Config::get('options.cliente')[$orden->orden_cliente_id] }}</td>
                                <td>{{ $orden->orden_fecha }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>N° Lote</th>
                                <th>Cliente</th>
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

@include('produccion.orden_produccion.modaladd')

@endsection