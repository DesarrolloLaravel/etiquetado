@extends('app')

@section('htmlheader_title')
    Productos
@endsection

@section('contentheader_title')
    Lista de Productos
@endsection

@section('main-content')

<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">

	var table;

	$(document).ready(function(){

        alert = new Alert('#notifications');
        $('.alert').hide();
        $('.select2').select2();

	    table = $('#table-productos').DataTable({
	        "ajax" : "producto",
	        "language": {
	            "url": "../../plugins/datatables/es_ES.txt"
	        },
	        "order": [[ 0, 'desc' ]],
	        "columnDefs": [{
	            "targets": -1,
	            "data": null,
	            "defaultContent": "<button class='btn btn-xs btn-primary' id='edit'><i class='fa fa-pencil'></i></button>\
	                <button class='btn btn-xs btn-danger' id='delete'><i class='fa fa-close'></i></button>"
	        }],
	        'fnCreatedRow': function (nRow, aData, iDataIndex) {
	            $(nRow).attr('data-id', aData[0]);
	        }
	    });

	    $("#add").click(function(){

            $('#modal_add').modal('show');
        });

        $('#table-productos tbody').on( 'click', '#edit', function () {
                producto_id = $(this).parents('tr').data('id');

                $.get("producto/edit",
                        {producto_id : producto_id},
                        function(data){

                            if(data[0] == "nok"){
                                
                                $('#modal_error').modal('show');
                                $(".alert").hide();
                            
                            }else{

                                $(".alert").hide();
                                setValues(data['producto'], 0);
                                $('#modal_edit').modal('show');
                            }
                            
                        });
        } );

        $('#table-productos tbody').on( 'click', '#delete', function () {

                producto_id = $(this).parents('tr').data('id');

                $.get("producto/edit",
                        {producto_id : producto_id},
                        function(data){

                            if(data[0] == "nok"){
                                
                                $('#modal_error').modal('show');
                                $(".alert").hide();
                            
                            }else{
                                

                                $(".alert").hide();
                                setValues(data['producto'], 1);

                                $("#form-delete :input")
                                    .not('.btn')
                                    .not("input[type='hidden']")
                                    .attr("disabled", true);
                                $('#modal_delete').modal('show');
                            }

                        });
        } );

        $("#update").click(function(){

                var form = $("#form-edit");
                //obtengo url
                var url = form.attr('action');
                //obtengo la informacion del formulario
                var data = form.serialize();

                $.post(url, data, function(resp)
                {
                    if(resp[0] == "ok")
                    {
                        alert.success("El registro fue actualizado exitosamente.")
                        //reseteo el formulario
                        $('#form-edit').trigger("reset");

                        $('#modal_edit').modal('hide');

                        table.ajax.reload();
                    }

                }).fail(function(resp){

                    var html = "";
                    for(var key in resp.responseJSON)
                    {
                        html += resp.responseJSON[key][0] + "<br>";
                    }
                    alert.error(html);
                });

        });

        $("#delete").click(function(){

                var form = $("#form-delete");
                //obtengo url
                var url = form.attr('action');
                //obtengo la informacion del formulario
                var data = form.serialize();
                var html = "";

                $.post(url, data, function(resp)
                {
                    if(resp[0] == "ok")
                    {
                        alert.success("El registro fue eliminado exitosamente.");
                        //reseteo el formulario
                        $('#form-delete').trigger("reset");

                        $('#modal_delete').modal('hide');

                        table.ajax.reload();
                    }

                }).fail(function(resp){

                    for(var key in resp.responseJSON)
                    {
                        html += resp.responseJSON[key][0] + "<br>";
                    }
                    alert.error(html);
                });

            });



        $("#save").click(function(){

            $.ajaxSetup({
                headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var form = $("#form-add");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            var data = form.serialize();

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    alert.success("El registro fue guardado exitosamente");
                    //reseteo el formulario
                    $('#form-add').trigger("reset");

                    $('#modal_add').modal('hide');

                    table.ajax.reload();
                }

            }).fail(function(resp){

                var html = "";
                for(var key in resp.responseJSON)
                {
                    html += resp.responseJSON[key][0] + "<br>";
                }
                alert.error(html);
            });

        });
	});

    function setValues(data, n)
        {
            var form = "form-edit";
            if (n == 1) {
                form = "form-delete";
            }

            $("#"+form+" input[name='producto_id']").val(data.producto_id);
            $("#"+form+" input[name='producto_nombre']").val(data.producto_nombre);
            $("#"+form+" input[name='producto_peso']").val(data.producto_peso);
            $("#"+form+" input[name='producto_codigo']").val(data.producto_codigo);
            $("#"+form+" select[name='producto_condicion']").val(data.producto_condicion_id).change();
            $("#"+form+" select[name='producto_trim']").val(data.producto_trim_id).change();
            $("#"+form+" select[name='producto_calidad']").val(data.producto_calidad_id).change();
            $("#"+form+" select[name='producto_variante']").val(data.producto_variante_id).change();
            $("#"+form+" select[name='producto_calibre']").val(data.producto_calibre_id).change();
            $("#"+form+" select[name='producto_v2']").val(data.producto_v2_id).change();
            $("#"+form+" select[name='producto_envase1']").val(data.producto_envase1_id).change();
            $("#"+form+" select[name='producto_envase2']").val(data.producto_envase2_id).change();
            $("#"+form+" select[name='producto_especie']").val(data.producto_especie_id).change();
            $("#"+form+" select[name='producto_formato']").val(data.producto_formato_id).change();
        }

</script>

<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i>
                        Agregar Producto
                    </a>
                    <br><br>
                    <table class="table table-bordered" id="table-productos" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Peso Est&aacute;ndar</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                            <tr role="row" data-id="{{ $producto->producto_id }}">
                                <td>{{ $producto->producto_id }}</td>
                                <td>{{ $producto->producto_nombre }}</td>
                                <td>{{ $producto->producto_peso }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Peso Est&aacute;ndar</th>
                                <th>Opciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('empaque.producto.modaladd')
@include('empaque.producto.modaledit')
@include('empaque.producto.modaldelete')
@include('empaque.producto.modalerror')

@endsection