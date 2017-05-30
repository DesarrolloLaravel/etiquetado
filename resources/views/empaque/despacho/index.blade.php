@extends('app')

@section('htmlheader_title')
    Despacho
@endsection

@section('contentheader_title')
    Despacho
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
    var table_etiqueta;
    var arr_cajas = [];
    var del_cajas = [];
    var arr_pallet = [];
    var orden_id;
    var etiqueta_pallet;
    var table_orden;

    $.ajaxSetup({
        headers:{
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){

        orden_id = $("#orden_id").val();
        $(".alert").hide();

        table = $('#table-despacho').DataTable({
            "ajax" : "despacho",
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "order": [[ 0, 'desc' ]],
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-xs btn-primary' id='edit'><i class='fa fa-pencil'></i></button>\
                <button class='btn btn-xs btn-danger' id='delete'><i class='fa fa-close'></i></button>\
                <button class='btn btn-xs btn-warning' id='despachar'><i class='fa fa-reply'></i></button>\
                <button class='btn btn-xs btn-success' id='excel'><i class='fa fa-file-excel-o'></i></button>"
            }],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('data-id', aData[0]);
            }
        });

        $('#table-despacho tbody').on( 'click', '#edit', function ()
        {
            $(".alert").hide();

            alert("edit");

            if(table_etiqueta != undefined)
            {
                table_etiqueta.destroy();
            }


            var despacho_id = $(this).parents('tr').data('id');

            $.get("despacho/edit",
                {despacho_id : despacho_id},
                function(data){

                if(data['estado'] == "ok"){

                    $('#modal_edit .modal-dialog .modal-content .modal-body').find('#form-edit').html(data['section']);

                    $('#modal_edit').modal('show');

                    setValues(data,0);

                    table_etiqueta = $('#table-etiqueta').DataTable({
                        "language": {
                            "url": "../../plugins/datatables/es_ES.txt"
                        },
                        "order": [[ 1, 'desc' ]],
                        "destroy": true,
                        "columnDefs": [{
                            "targets": -1,
                            "data": null,
                            "defaultContent": "<a class='btn btn-xs btn-danger' id='d_return'><i class='fa fa-close'></i></a>"
                        }],
                        'fnCreatedRow': function (nRow, aData, iDataIndex) {
                            $(nRow).attr('data-id', aData[0]);
                        }
                    });

                    $('#table-etiqueta tbody').on('click', '#d_return', function () {
                        
                        alert("caja delete");
                        var caja_id = $(this).parents('tr').data('id');

                        var index = arr_cajas.indexOf(caja_id);

                        arr_pallet.splice(index);
                        arr_cajas.splice(index);

                        del_cajas.push(caja_id);

                        table_etiqueta.row( $(this).parents('tr') )
                            .remove()
                            .draw();
                    });

                    alert(data.resp.orden_cajas.length);

                    for (var i = 0; i < data.resp.orden_cajas.length; i++) {
                        
                        table_etiqueta.row.add( [
                            data.resp.orden_cajas[i].id,
                            data.resp.orden_detalle[i].producto,
                            data.resp.orden_cajas[i].codigo,
                            data.resp.orden_cajas[i].kilos
                        ] ).draw( false );

                        arr_pallet.push(data.resp.orden_cajas[i].codigo);
                        arr_cajas.push(data.resp.orden_cajas[i].id);
                    }
                }
            });
                
        });

        $(document).on('click','#update',function(event){

            alert("doc");

            var form = $("#form-edit");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            alert(url);
            $("#form-edit #num_despacho").attr("disabled", false);
            $("#form-edit #despacho_estado").attr("disabled", false);
            
            var data = form.serialize()+ '&cajas=' + arr_cajas + '&etiquetas=' + arr_pallet + '&del='+del_cajas;

            alert(data);
            
            
            $("#form-edit #num_despacho").attr("disabled", true);
            $("#form-edit #despacho_estado").attr("disabled",true);

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El registro fue actualizado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-edit').trigger("reset");

                    $('#modal_edit').modal('hide');

                    table_etiqueta.destroy();
                    arr_pallet = [];
                    arr_cajas = [];
                    delete_caja = [];

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

        $('#table-despacho tbody').on( 'click', '#delete', function ()
        {
            $(".alert").hide();
            alert("delete");

            despacho_id = $(this).parents('tr').data('id');

            alert(despacho_id);

            $.get("despacho/edit",
                {despacho_id : despacho_id},
                function(data){
                    
                    if(data[0] == "nok"){
                        $('#modal_error').modal('show');
                    }
                    else{
                        
                        $('#modal_delete .modal-dialog .modal-content .modal-body').find('#form-delete').html(data['section']);

                        $('#modal_delete').modal('show');

                        setValues(data,1);

                        table_etiqueta = $('#table-etiqueta').DataTable({
                            "language": {
                                "url": "../../plugins/datatables/es_ES.txt"
                            },
                            "order": [[ 1, 'desc' ]],
                            "destroy": true,
                            "columnDefs": [{
                                "targets": -1,
                                "data": null,
                                "defaultContent": "<a class='btn btn-xs btn-danger' id='d_return'><i class='fa fa-close'></i></a>"
                            }],
                            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                                $(nRow).attr('data-id', aData[0]);
                            }
                        });

                        alert(data.resp.orden_cajas.length);

                        for (var i = 0; i < data.resp.orden_cajas.length; i++) {
                            
                            table_etiqueta.row.add( [
                                data.resp.orden_cajas[i].id,
                                data.resp.orden_detalle[i].producto,
                                data.resp.orden_cajas[i].codigo,
                                data.resp.orden_cajas[i].kilos
                            ] ).draw( false );

                            arr_pallet.push(data.resp.orden_cajas[i].codigo);
                            arr_cajas.push(data.resp.orden_cajas[i].id);
                        }

    
                    }

                });
        } );

       $(document).on('click','#borrar',function(event){

            alert("doc");

            var form = $("#form-delete");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            alert(url);
            $("#form-delete #num_despacho").attr("disabled", false);
            
            var data = form.serialize();

            alert(data);
            
            $("#form-delete #num_despacho").attr("disabled", true);

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El registro fue actualizado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-delete').trigger("reset");

                    $('#modal_delete').modal('hide');

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

        $('#table-despacho tbody').on( 'click', '#excel', function ()
        {
            $(".alert").hide();

            var despacho_id = $(this).parents('tr').data('id');

            alert(despacho_id);
            var url = location.href+"/imprimir_informe/"+despacho_id;
            window.open(url, '_blank');
        } );

        $("#table-despacho tbody").on( 'click', '#despachar', function ()
        {
            $(".alert").hide();
            alert("excel");

            despacho_id = $(this).parents('tr').data('id');

            alert(despacho_id);

            $.get("despacho/despachar",
                {despacho_id : despacho_id},
                function(data){
                    
                    if(data['estado'] == "nok"){
                        $('#modal_error').modal('show');
                    }
                    else{
                        
                        table.ajax.refresh();
                    }

            });
        });

        $("#add").click(function(){

            arr_pallet = [];
            arr_cajas = [];

            if(table_etiqueta != undefined)
            {
                table_etiqueta.destroy();
            }

            $.get('despacho/create', function(data){

                $('#modal_add .modal-dialog .modal-content .modal-body').find('#form-add').html(data['section']);

                $('#modal_add').modal('show');

                table_etiqueta = $('#table-etiqueta').DataTable({
                    "language": {
                        "url": "../../plugins/datatables/es_ES.txt"
                    },
                    "destroy": true,
                    "order": [[ 1, 'desc' ]],
                    "columnDefs": [{
                        "targets": -1,
                        "data": null,
                        "defaultContent": "<a class='btn btn-xs btn-danger' id='delete_caja'><i class='fa fa-close'></i></a>"
                    }],
                    'fnCreatedRow': function (nRow, aData, iDataIndex) {
                        $(nRow).attr('data-id', aData[0]);
                    }
                });

                $('#table-etiqueta tbody').on('click', '#delete_caja', function () {

                    var caja_id = $(this).parents('tr').data('id');

                    var index = arr_pallet.indexOf(caja_id);

                    arr_pallet.splice(index);
                    arr_cajas.splice(index);
                    

                    table_etiqueta.row( $(this).parents('tr') )
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

        $(document).on('click','#orden_search',function(event){

            $('.alert').hide();

            if(table_orden != undefined)
            {
                table_orden.destroy();
            }

            table_orden = $('#table-orden').DataTable({
                "ajax" : "ordenproduccion",
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
        });

        $('#table-orden tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        $('#select_orden').click( function () {

            if(table_orden.rows('tr.selected').data().length > 0)
            {
                orden_id = $("#table-orden").find('tbody tr.selected').data('id');
                $("#modal_orden").modal('hide');
                $("#orden_id").val(orden_id);
            }
            else
            {
                alert("Debes seleccionar una ORDEN");
            }
        });

        $(document).on('click','#add_pallet',function(event){

            alert("add pallet");

            etiqueta = $("#etiqueta_ide").val();
            orden_id = $("#orden_id").val();

            alert(etiqueta_pallet);
            alert(orden_id);

            if(etiqueta_pallet != '')
            {
                in_array = false;

                if(arr_pallet.indexOf(etiqueta_pallet) >= 0)
                {
                    in_array = true;
                }

                if(in_array)
                {
                    alert("La etiqueta ya fue agregada a la lista.")
                }
                else
                {

                    $.get('despacho/cargar_etiqueta',{orden_id : orden_id,etiqueta : etiqueta},function(data){

                        if(data['estado'] == "nok"){

                            $(".alert-success").hide();
                            $(".alert-danger").html("La etiqueta no se encuentra registrada").show();    

                        }else{

                            $(".alert-danger").hide();
                            table_etiqueta.row.add( [
                                data['caja'].caja_id,
                                data['producto'],
                                etiqueta,
                                data['caja'].caja_peso_real
                            ] ).draw( false );

                            arr_cajas.push(data['caja'].caja_id);
                            arr_pallet.push(etiqueta);        

                        }
                    });
                }
            }
            else
            {
            alert("Debes seleccionar una Etiqueta");
            }
        });


        $(document).on('click',"#saves",function(event){

            var form = $("#form-add");
            //obtengo url
            var url = form.attr('action');

            $( "#orden_id" ).prop( "disabled", false );

            alert(url);
            //obtengo la informacion del formulario
            var data = form.serialize()+ '&cajas=' + arr_cajas + '&etiquetas='+ arr_pallet;

            alert(data);

            $( "#orden_id" ).prop( "disabled", true );

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El registro fue guardado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-add').trigger("reset");

                    $('#modal_add').modal('hide');
                    console.log(table_etiqueta);
                    table_etiqueta.destroy();
                    console.log(table_etiqueta);
                    arr_pallet = [];
                    arr_cajas = [];

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
    });

    function setValues(data, n)
    {
        var form = "form-edit";
        if (n == 1) {
            form = "form-delete";

            $("#"+form+" input[name='etiqueta_pallet']").prop("disabled", true);
            $("#"+form+" #add_pallet").prop("disabled", true);
        }

        $("#"+form+" input[name='orden_id']").val(data.resp['orden_orden_produccion']);
        $("#"+form+" input[name='despacho_guia']").val(data.resp['orden_guia']);
        $("#"+form+" select[name='despacho_estado']").val(data.estatus);

        $("#"+form+" select[name='despacho_estado']").prop("disabled", true);
        $("#"+form+" input[name='despacho_guia']").prop("disabled", true);
        $("#"+form+" input[name='despacho_guia']").prop("disabled", true);
        $("#"+form+" input[name='despacho_fecha']").prop("disabled", true);
        $("#"+form+" button[name='orden_search']").prop("disabled", true);
    }

</script>
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i>
                        Agregar Despacho
                    </a>
                    <br><br>
                    <a class="btn btn-default disabled" id="despachos">
                        Despachos
                    </a>
                    <a class="btn btn-primary" href="{{ url('empaque/despacho/despachados') }}" id="despachados">
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

@include('empaque.despacho.modaladd')
@include('empaque.despacho.modalorden')
@include('empaque.despacho.modaledit')
@include('empaque.despacho.modaldelete')



@endsection