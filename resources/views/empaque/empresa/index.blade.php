@extends('app')

@section('htmlheader_title')
    Empresas
@endsection

@section('contentheader_title')
    Empresas
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
	var table;

    $(document).ready(function(){

        $(".alert").hide();

        table = $('#table-empresas').DataTable({
            "ajax" : "empresa",
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

        $('#table-empresas tbody').on( 'click', '#edit', function ()
        {
            $(".alert").hide();

            empresa_id = $(this).parents('tr').data('id');

            $.get("empresa/edit",
                {empresa_id : empresa_id},
                function(data){
                    setValues(data, 0);

                    $('#modal_edit').modal('show');

                });
        } );

        $('#table-empresas tbody').on( 'click', '#delete', function ()
        {
            $(".alert").hide();

            empresa_id = $(this).parents('tr').data('id');

            $.get("empresa/edit",
                {empresa_id : empresa_id},
                function(data){
                    setValues(data, 1);

                    $('#modal_delete').modal('show');

                    $("#form-delete :input")
                        .not('.btn')
                        .not("input[type='hidden']")
                        .attr("disabled", true);

                });
        } );

        $("#add").click(function(){

            $('#modal_add').modal('show');
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
                    $(".alert-success").html("El registro fue guardado exitosamente").show();
                    $(".alert-danger").hide();
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
                $(".alert-success").hide();
                $(".alert-danger").html(html).show();
            });

        });

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
                    $(".alert-success").html("El registro fue actualizado exitosamente").show();
                    $(".alert-danger").hide();
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
                $(".alert-success").hide();
                $(".alert-danger").html(html).show();
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
                    $(".alert-success").html("El registro fue elimianado exitosamente").show();
                    $(".alert-danger").hide();
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
                $(".alert-success").hide()
                $(".alert-danger").html(html).show();
            });

        });

    });

    function setValues(data, n)
    {
        var form = "form-edit";
        if (n == 1) {
            form = "form-delete";
        }

        $("#"+form+" input[name='empresa_id']").val(data.empresa_id);
        $("#"+form+" input[name='empresa_name']").val(data.empresa_name);
        $("#"+form+" input[name='empresa_rut']").val(data.empresa_rut);
    }
</script>
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i>
                        Agregar Empresa
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-empresas" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Empresa</th>
                                <th>RUT</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($empresas as $empresa)
                            <tr role="row" data-id="{{ $empresa->empresa_id }}">
                                <td>{{ $empresa->empresa_id }}</td>
                                <td>{{ $empresa->empresa_name }}</td>
                                <td>{{ $empresa->empresa_rut }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Empresa</th>
                                <th>RUT</th>
                                <th>Opciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('empaque.empresa.modaladd')
@include('empaque.empresa.modaledit')
@include('empaque.empresa.modaldisable')

@endsection