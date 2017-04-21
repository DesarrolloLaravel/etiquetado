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
    var table_products;
    var arr_products = [];
    var arr_kilos = [];

    $(document).ready(function(){

        $('.select2').select2();

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
                        arr['Descripción'] = data.orden_descripcion;
                        var i = 0;
                        for (var key in data.orden_productos) {
                            arr['Producto '+(i+1)] = key;
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

        $('#table-ordenes tbody').on( 'click', '#edit', function ()
        {
            arr_products = [];
            arr_kilos =[];
            if(table_products != undefined)
            {
                table_products.destroy();
            }

            $(".alert").hide();

            orden_id = $(this).parents('tr').data('id');

            $.get("ordenproduccion/edit",
                {orden_id : orden_id},
                function(data){
                    if(data['estado'] == "ok")
                    {
                        $('#modal_edit .modal-dialog .modal-content .modal-body').find('#form-edit').html(data['section']);
                        $('#modal_edit').modal('show');

                        $('.select2').select2();

                        setValues(data['orden'], 0);

                        $('.datepicker').datepicker({
                            format : 'dd-mm-yyyy',
                            autoclose: true,
                            language : 'es'
                        });

                    }

                });
        } );

         $('#modal_add .modal-dialog .modal-content .modal-body').on('change','#especie_id',function() {


            var especie_id = $(this).val();

            
            $.get('ordenproduccion/cargar_producto',{especie_id:especie_id},function(data){

                $('#producto_ide').empty()
                
                
                $.each(data, function(key, element) {

                    $('#producto_ide').append("<option value='" + key +"'>" + element + "</option>");
                });

                $('#producto_ide').append("<option value=' '">"Ninguno</option>");
            });
                    
        });


        $("#add").click(function(){

            arr_products = [];
            arr_kilos = [];

            if(table_products != undefined)
            {
                table_products.destroy();
            }

            $.get('ordenproduccion/create',
                function(data){

                    $('#modal_add .modal-dialog .modal-content .modal-body').find('#form-add').html(data['section']);
                    $('#modal_add').modal('show');

                    $('.select2').select2();

                    table_products = $('#table-products').DataTable({
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

                        var index = arr_products.indexOf(product_id);

                        arr_products.splice(index);
                        arr_kilos.splice(index);

                        table_products.row( $(this).parents('tr') )
                            .remove()
                            .draw();
                    });

                    $('.datepicker').datepicker({
                        format : 'dd-mm-yyyy',
                        autoclose: true,
                        language : 'es'
                    });

                });
        });

        $(document).on('click','#save',function(event){

            var form = $("#form-add");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario

            var data = form.serialize()+ '&productos=' + arr_products+ '&kilos='+arr_kilos;

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El registro fue guardado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-add').trigger("reset");

                    $('#modal_add').modal('hide');
                    console.log(table_products);
                    table_products.destroy();
                    console.log(table_products);
                    arr_products = [];
                    arr_kilos = [];

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

        $(document).on('click','#update',function(event){

            var form = $("#form-edit");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            $("#form-edit #orden_id").attr("disabled", false);
            var data = form.serialize()+ '&productos=' + arr_products + '&kilos='+ arr_kilos;
            $("#form-edit #orden_id").attr("disabled", true);

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El registro fue actualizado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-edit').trigger("reset");

                    $('#modal_edit').modal('hide');

                    table_products.destroy();
                    arr_products = [];
                    arr_kilos = [];

                    table.ajax.reload();
                }

            }).fail(function(resp){
                $('#modal_edit').animate({ scrollTop: 0 }, 'slow');
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

            product_id = $("#producto_ide").val();

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
                    $.get('ordenproduccion/kilos_def',{product_id : product_id},function(data){
                    
                        var def = 0;
                        def = (data.producto_peso - $("#kilos_id").val());
                        table_products.row.add( [
                            product_id,
                            $("#especie_id option:selected").text(),
                            $("#producto_ide option:selected").text(),
                            $("#kilos_id").val(),
                            data.producto_peso,
                            def
                        ] ).draw( false );

                        

                        var kilos = $("#kilos_id").val();
                        var producto_ides = product_id;

                        arr_products.push(producto_ides);
                        arr_kilos.push(kilos);
                    });
                }
            }
            else
            {
            alert("Debes seleccionar un PRODUCTO");
            }
        });

    });

    function setValues(data, n)
    {
        var form = "form-edit";
        if (n == 1) {
            form = "form-delete";
        }

        $("#"+form+" input[name='orden_number']").val(data.orden_id);
        $("#"+form+" select[name='orden_cliente_id']").val(data.orden_cliente_id);
        $("#"+form+" input[name='orden_descripcion']").val(data.orden_descripcion);
       
        table_products = $(document).find('#'+form+' #table-products').DataTable({
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

        $('#'+form+' #table-products tbody').on('click', '#delete_product', function () {
            product_id = $(this).parents('tr').data('id');

            arr_products.splice(arr_products.indexOf(product_id));

            table_products.row( $(this).parents('tr') )
                .remove()
                .draw();
        });

        for (var i = 0; i < data.productos.length; i++) {
            console.log(data.productos[i]);
            table_products.row.add( [
                data.productos[i].op_producto_producto_id
            ] ).draw( false );
            arr_products.push(data.productos[i].op_producto_producto_id);
        };
        
    }

</script>
    <div class="row">
        <div class="col-md-12">
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
                                <th>Cliente</th>
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

@include('admin.orden_produccion.modaladd')
@include('admin.orden_produccion.modaledit')

@endsection