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
    var arr_etiquetas = [];
    var lote_add = 0;
   
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
                "defaultContent": "<button class='btn btn-xs btn-warning' id='return'><i class='fa fa-reply'></i></button>\
                    <button class='btn btn-xs btn-primary' id='edit'><i class='fa fa-pencil'></i></button>\
                    <button class='btn btn-xs btn-danger' id='delete'><i class='fa fa-close'></i></button>"
            }],

            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('data-id', aData[1]);
            }
        });
    
        $("#add").click(function(){
        $('.alert').hide();

            arr_etiquetas = [];

            if(table_pallet != undefined)
            {
                table_pallet.destroy();
            }

            $.get('ordentrabajo/create',function(data){

                $('#modal_add .modal-dialog .modal-content .modal-body').find('#form-adds').html(data['section']);
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
                        "defaultContent": "<a class='btn btn-xs btn-danger' id='delete_pallet'><i class='fa fa-close'></i></a>"
                    }],
                    'fnCreatedRow': function (nRow, aData, iDataIndex) {
                        $(nRow).attr('data-id', aData[0]);
                    }
                });

                $('#table-pallet tbody').on('click', '#delete_pallet', function () {
                        
                    etiqueta_id = $(this).parents('tr').data('id');

                    var index = arr_etiquetas.indexOf(etiqueta_id);

                    arr_etiquetas.splice(index);

                    table_pallet.row( $(this).parents('tr') )
                        .remove()
                        .draw();


                    if(arr_etiquetas.length == 0){


                        $( "#producto_ide" ).prop( "disabled", false );
                        $( "#especie_id" ).prop( "disabled", false );
                        $( "#orden_prod" ).prop( "disabled", false );

                    }

                }); 

                $('.datepicker').datepicker({
                    format : 'dd-mm-yyyy',
                    autoclose: true,
                    language : 'es'
                });

            });
        });

        $(document).on('click','#update',function(event){


            if(arr_etiquetas.length == 0){

                $('#modal_edit').modal('hide');
                table.ajax.reload();
            }


            var form = $("#form-edit");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            $("#form-edit #orden_trabajo_id").attr("disabled", false);

             var data = form.serialize()  + '&etiquetas=' + arr_etiquetas;
            
            $("#form-edit #orden_trabajo_id").attr("disabled", true);

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El registro fue actualizado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-edit').trigger("reset");

                    $('#modal_edit').modal('hide');

                    table_pallet.destroy();
                    arr_etiquetas = [];

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

        $('#modal_add .modal-dialog .modal-content .modal-body').on('change','#orden_prod',function() {


            var orden_prod = $(this).val();

            
            $.get('ordentrabajo/cargar_especie',{orden_prod:orden_prod},function(data){

                $('#especie_id').empty();
                
                $('#especie_id').append("<option value='#'> Ninguno </option>");

                $.each(data, function(key, element) {

                    $('#especie_id').append("<option value='" + key +"'>" + element + "</option>");
                });
                
            });
                    
        });

        $('#modal_add .modal-dialog .modal-content .modal-body').on('change','#especie_id',function() {


            var especie_id = $(this).val();

            
            $.get('ordentrabajo/cargar_producto',{especie_id:especie_id},function(data){

                $('#producto_ide').empty();

                $('#producto_ide').append("<option value='#'> Ninguno </option>");
                
                $.each(data, function(key, element) {

                    $('#producto_ide').append("<option value='" + key +"'>" + element + "</option>");
                });
            });
                    
        });

        $(document).on('click','#add_pallet',function(event){

            etiqueta_pallet = $("#etiqueta_ide").val();
            producto_id = $("#producto_ide").val();

            if(etiqueta_pallet != '' && producto_id!= '')
            {
                in_array = false;

                if(arr_etiquetas.indexOf(etiqueta_pallet) >= 0)
                {
                    in_array = true;
                }

                if(in_array)
                {

                     $(".alert-danger").html("La etiqueta ya fue agregada a la lista.").show();    

                }
                else
                {
                    $.get('ordentrabajo/kilos_eti',{producto_id : producto_id,etiqueta_pallet : etiqueta_pallet},function(data){

                        if(data['estado'] == "nok"){

                            $(".alert-success").hide();
                            $(".alert-danger").html("La etiqueta no se encuentra registrada").show();    

                        }else{

                            if(lote_add == 0){
                                lote_add = data['dato'].etiqueta_mp_lote_id;    

                                $(".alert-danger").hide();

                                table_pallet.row.add( [
                                    data['dato'].etiqueta_mp_id,
                                    data['dato'].etiqueta_mp_lote_id,
                                    $("#etiqueta_ide").val(),
                                    data['dato'].etiqueta_mp_peso
                                ] ).draw( false );
                                
                                arr_etiquetas.push(etiqueta_pallet);


                                $( "#producto_ide" ).prop( "disabled", true );
                                $( "#especie_id" ).prop( "disabled", true );
                                $( "#orden_prod" ).prop( "disabled", true );      
                            
                            }else{

                                if(lote_add == data['dato'].etiqueta_mp_lote_id){
                                    $(".alert-danger").hide();

                                    table_pallet.row.add( [
                                        data['dato'].etiqueta_mp_id,
                                        data['dato'].etiqueta_mp_lote_id,
                                        $("#etiqueta_ide").val(),
                                        data['dato'].etiqueta_mp_peso
                                    ] ).draw( false );
                                
                                    arr_etiquetas.push(etiqueta_pallet);


                                    $( "#producto_ide" ).prop( "disabled", true );
                                    $( "#especie_id" ).prop( "disabled", true );
                                    $( "#orden_prod" ).prop( "disabled", true );    
                                }
                                else{

                                    $(".alert-success").hide();
                                    $(".alert-danger").html("La etiqueta no coincide con el lote").show(); 
                                }
                            }
                        }
                    });
                }
            }
            else
            {
            $(".alert-danger").html("Debes seleccionar una Etiqueta Y/O rellenar los campos iniciales").show(); 
            }


        });

        $(document).on('click','#guardar',function(event){


            $( "#producto_ide" ).prop( "disabled", false );
            $( "#especie_id" ).prop( "disabled", false );
            $( "#orden_prod" ).prop( "disabled", false );

            var form = $("#form-adds");
            //obtengo url
            var url = form.attr('action');
            
            //obtengo la informacion del formulario
            var data = form.serialize()  + '&etiquetas=' + arr_etiquetas;          

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El registro fue guardado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-adds').trigger("reset");

                    $('#modal_add').modal('hide');
                    console.log(table_pallet);
                    table_pallet.destroy();
                    console.log(table_pallet);
                    arr_etiquetas = [];

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

        $('#table-trabajos tbody').on( 'click', '#edit', function ()
        {   

            arr_etiquetas = [];
            if(table_pallet != undefined)
            {
                table_pallet.destroy();
            }

            $(".alert").hide();

            orden_id = $(this).parents('tr').data('id');

            $.get("ordentrabajo/edit",
                {orden_id : orden_id},
                function(data){
                    if(data['estado'] == "ok")
                    {
                        $('#modal_edit .modal-dialog .modal-content .modal-body').find('#form-edit').html(data['section']);
                        $('#modal_edit').modal('show');

                        $('.select2').select2();

                        table_pallet = $('#table-pallet').DataTable({
                            "language": {
                                "url": "../../plugins/datatables/es_ES.txt"
                            },
                            "order": [[ 1, 'desc' ]],
                            "columnDefs": [{
                                "targets": -1,
                                "data": null,
                                "defaultContent": ""
                            }],
                            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                                $(nRow).attr('data-id', aData[0]);
                            }
                        });
                        
                        lote_add = data.pallet[0].etiqueta_mp_lote_id;
                                              
                        for (var i = 0; i < data.pallet.length; i++) {
                            
                            table_pallet.row.add( [
                                data.pallet[i].etiqueta_mp_id,
                                data.pallet[i].etiqueta_mp_lote_id,
                                data.pallet[i].etiqueta_mp_barcode,
                                data.pallet[i].etiqueta_mp_peso
                            ] ).draw( false );

                            //arr_etiquetas.push(data.pallet[i].etiqueta_mp_barcode);
                        }

                        $( "#producto_ide" ).prop( "disabled", true );
                        $( "#especie_id" ).prop( "disabled", true );
                        $( "#orden_prod" ).prop( "disabled", true );  
                        $( "#orden_fecha" ).prop( "disabled", true );   
                        
                
                        $('.datepicker').datepicker({
                            format : 'dd-mm-yyyy',
                            autoclose: true,
                            language : 'es'
                        });

                    }

                });
        } );

        $('#table-trabajos tbody').on( 'click', '#return', function ()
        {   

            arr_etiquetas = [];

            if(table_pallet != undefined)
            {
                table_pallet.destroy();
            }

            $(".alert").hide();

            orden_id = $(this).parents('tr').data('id');

            $.get("ordentrabajo/edit",
                {orden_id : orden_id},function(data){
                    
                if(data['estado'] == "ok")
                {
                    $('#modal_return .modal-dialog .modal-content .modal-body').find('#form-return').html(data['section']);
                    $('#modal_return').modal('show');

                    $('.select2').select2();

                    table_pallet = $('#table-pallet').DataTable({
                        "language": {
                            "url": "../../plugins/datatables/es_ES.txt"
                        },
                        "order": [[ 1, 'desc' ]],
                        "columnDefs": [{
                            "targets": -1,
                            "data": null,
                            "defaultContent": "<a class='btn btn-xs btn-danger' id='d_return'><i class='fa fa-reply'></i></a>"
                        }],
                        'fnCreatedRow': function (nRow, aData, iDataIndex) {
                            $(nRow).attr('data-id', aData[0]);
                        }
                    });

                    lote_add = data.pallet[0].etiqueta_mp_lote_id;

                    for (var i = 0; i < data.pallet.length; i++) {
                        
                        table_pallet.row.add( [
                            data.pallet[i].etiqueta_mp_id,
                            data.pallet[i].etiqueta_mp_lote_id,
                            data.pallet[i].etiqueta_mp_barcode,
                            data.pallet[i].etiqueta_mp_peso
                        ] ).draw( false );

                        //arr_etiquetas.push(data.pallet[i].etiqueta_mp_barcode);
                    }

                    $( "#producto_ide" ).prop( "disabled", true );
                    $( "#especie_id" ).prop( "disabled", true );
                    $( "#orden_prod" ).prop( "disabled", true );  
                    $( "#orden_fecha" ).prop( "disabled", true ); 
                    $( "#etiqueta_ide" ).prop( "disabled", true );
                    $( "#add_pallet" ).prop( "disabled", true ); 


                    $('#table-pallet tbody').on('click', '#d_return', function () {
                        
                        var etiqueta_id = $(this).parents('tr').data('id');
                        $("#form-return #orden_trabajo_id").attr("disabled", false);

                        var orden = $( "#orden_prod" ).val();

                        $("#form-return #orden_trabajo_id").attr("disabled", true);


                        //arr_etiquetas.push(etiqueta_id);
                        

                        /*table_pallet.row( $(this).parents('tr') )
                            .remove()
                            .draw();*/

                        $.get("ordentrabajo/rec_etiqueta",
                        {orden : orden, etiqueta_id : etiqueta_id},function(data){



                            

                            $("#form-returne input[name='orden_number']").val(orden);
                            $("#form-returne input[name='orden_etid']").val(etiqueta_id);
                            $("#form-returne input[name='orden_etiqueta']").val(data.eti.etiqueta_mp_barcode);
                            $("#form-returne input[name='orden_kilos_actual']").val(data.eti.etiqueta_mp_peso);
                            $("#form-returne input[name='orden_cajas_actual']").val(data.eti.etiqueta_mp_cantidad_cajas);
                            $('#modal_etiqueta').modal('show');

                        });
                    });
                    

            
                    $('.datepicker').datepicker({
                        format : 'dd-mm-yyyy',
                        autoclose: true,
                        language : 'es'
                    });

                }


            });

            var printPage = window.open('{{ url("admin/etiqueta_mp/print") }}'+'/'+etiqueta_id, '');
            alert(printPage);
        });

        $('#table-trabajos tbody').on( 'click', '#delete', function ()
        {   

            $(".alert").hide();

            id = $(this).parents('tr').data('id');

            $.get("ordentrabajo/pre_borrado",
                {id : id},function(data){
                    
                if(data['estado'] == "ok")
                {
                    $('#modal_delete .modal-dialog .modal-content .modal-body').find('#form-delete').html(data['section']);
                    $('#modal_delete').modal('show');

                    $('.select2').select2();

                    table_pallet = $('#table-pallet').DataTable({
                        "language": {
                            "url": "../../plugins/datatables/es_ES.txt"
                        },
                        "order": [[ 1, 'desc' ]],
                        "columnDefs": [{
                            "targets": -1,
                            "data": null,
                            "defaultContent": ""
                        }],
                        'fnCreatedRow': function (nRow, aData, iDataIndex) {
                            $(nRow).attr('data-id', aData[0]);
                        }
                    });

                    for (var i = 0; i < data.pallet.length; i++) {
                        
                        table_pallet.row.add( [
                            data.pallet[i].etiqueta_mp_id,
                            data.pallet[i].etiqueta_mp_lote_id,
                            data.pallet[i].etiqueta_mp_barcode,
                            data.pallet[i].etiqueta_mp_peso
                        ] ).draw( false );

                        //arr_etiquetas.push(data.pallet[i].etiqueta_mp_barcode);
                    }

                    $( "#producto_ide" ).prop( "disabled", true );
                    $( "#especie_id" ).prop( "disabled", true );
                    $( "#orden_prod" ).prop( "disabled", true );  
                    $( "#orden_fecha" ).prop( "disabled", true );   
                    $( "#etiqueta_ide" ).prop( "disabled", true );
                    $( "#add_pallet" ).prop( "disabled", true );                        
                
                    $('.datepicker').datepicker({
                        format : 'dd-mm-yyyy',
                        autoclose: true,
                        language : 'es'
                    });

                }
            });

        });

        $(document).on('click','#borrar',function(event){

            
            var form = $("#form-delete");
            //obtengo url
            var url = form.attr('action');

            $("#form-delete #orden_trabajo_id").attr("disabled", false);
            //obtengo la informacion del formulario
            var data = form.serialize();          



            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El registro fue guardado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-delete').trigger("reset");

                    $('#modal_delete').modal('hide');
                    console.log(table_pallet);
                    table_pallet.destroy();
                    console.log(table_pallet);
                    arr_etiquetas = [];

                    table.ajax.reload();
                }

            }).fail(function(resp){
                $('#modal_delete').animate({ scrollTop: 0 }, 'slow');
                var html = "";
                for(var key in resp.responseJSON)
                {
                    html += resp.responseJSON[key][0] + "<br>";
                }
                $(".alert-success").hide();
                $(".alert-danger").html(html).show();
            });
        });


        $(document).on('click','#update_return',function(event){


            var form = $("#form-returne");
            //obtengo url
            var url = form.attr('action');

            $("#form-returne #orden_trabajo_id").attr("disabled", false);
            $("#form-returne #orden_etiqueta").attr("disabled", false);
            $("#form-returne #orden_kilos_actual").attr("disabled", false);
            $("#form-returne #orden_cajas_actual").attr("disabled", false);

            var data = form.serialize();
            


            $("#form-returne #orden_trabajo_id").attr("disabled", true);
            $("#form-returne #orden_etiqueta").attr("disabled", true);
            $("#form-returne #orden_kilos_actual").attr("disabled", true);
            $("#form-returne #orden_cajas_actual").attr("disabled", true);

            $.post(url, data, function(resp)
            {
                if(resp['estado'] == "nok")
                {
                    $(".alert-danger").html("Las cajas y los kilos no deben ser mayores a los actuales").show();

                }else{

                    $(".alert-success").html("El registro fue actualizado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-returne').trigger("reset");

                    $('#modal_etiqueta').modal('hide');
                    $('#modal_return').modal('hide');

                    table_pallet.destroy();
                    table.ajax.reload();
                   
                }

            }).fail(function(resp){
                $('#modal_return').animate({ scrollTop: 0 }, 'slow');
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
@include('admin.orden_trabajo.modalreturn')
@include('admin.orden_trabajo.modaldelete')
@include('admin.orden_trabajo.modalreturnetiqueta')
@endsection