@extends('app')

@section('htmlheader_title')
    Procesador
@endsection

@section('contentheader_title')
    Procesador
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
    var table;

    $(document).ready(function(){

        $(".alert").hide();

        table = $('#table-procesadores').DataTable({
            "ajax" : "procesador",
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

        $('#table-procesadores tbody').on( 'click', '#edit', function ()
        {
            $(".alert").hide();

            procesador_id = $(this).parents('tr').data('id');

            $.get("procesador/edit",
                {procesador_id : procesador_id},
                function(data){
                    console.log(data);
                    setValues(data, 0);

                    $('#modal_edit').modal('show');

                });
        } );

        $('#table-procesadores tbody').on( 'click', '#delete', function ()
        {
            $(".alert").hide();

            procesador_id = $(this).parents('tr').data('id');

            $.get("procesador/edit",
                {procesador_id : procesador_id},
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

        $("#"+form+" input[name='procesador_id']").val(data.procesador_id);
        $("#"+form+" input[name='procesador_name']").val(data.procesador_name);
        $("#"+form+" input[name='procesador_rut']").val(data.procesador_rut);
    }

</script>
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i>
                        Agregar Procesadora
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-procesadores" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Procesadora</th>
                                <th>RUT</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($procesadores as $procesador)
                            <tr role="row" data-id="{{ $procesador->procesador_id }}">
                                <td>{{ $procesador->procesador_id }}</td>
                                <td>{{ $procesador->procesador_name }}</td>
                                <td>{{ $procesador->procesador_rut }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Procesadora</th>
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

@include('admin.procesador.modaladd')
@include('admin.procesador.modaledit')
@include('admin.procesador.modaldisable')

@endsection