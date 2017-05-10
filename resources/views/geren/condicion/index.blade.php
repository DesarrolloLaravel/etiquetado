@extends('app')

@section('htmlheader_title')
    Condición
@endsection

@section('contentheader_title')
    Condición
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
    var table;

    $(document).ready(function(){

        $(".alert").hide();

        table = $('#table-condicion').DataTable({
            "ajax" : "condicion",
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

        $('#table-condicion tbody').on( 'click', '#edit', function ()
        {
            $(".alert").hide();

            condicion_id = $(this).parents('tr').data('id');

            $.get("condicion/edit",
                {condicion_id : condicion_id},
                function(data){

                    if(data[0] == "nok"){
                        $('#modal_error').modal('show');
                    }
                    else{
                        console.log(data);
                        setValues(data, 0);

                        $('#modal_edit').modal('show');
    
                    }
                });
        } );

        $('#table-condicion tbody').on( 'click', '#delete', function ()
        {
            $(".alert").hide();

            condicion_id = $(this).parents('tr').data('id');

            $.get("condicion/edit",
                {condicion_id : condicion_id},
                function(data){
                    
                    if(data[0] == "nok"){
                        $('#modal_error').modal('show');
                    }
                    else{
                        
                        setValues(data, 1);

                        $('#modal_delete').modal('show');

                        $("#form-delete :input")
                            .not('.btn')
                            .not("input[type='hidden']")
                            .attr("disabled", true);    
    
                    }

                });
        } );

        $("#add").click(function(){

            $('#form-add').trigger("reset");
            $(".alert").hide();
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
                }else{
                    $(".alert-success").hide();
                    $(".alert-danger").html("Ya existe una Condición con ese nombre, revise su información").show();    
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

        $("#"+form+" input[name='condicion_id']").val(data.condicion_id);
        $("#"+form+" input[name='condicion_name']").val(data.condicion_name);
        
    }

</script>
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i>
                        Agregar Condición
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-condicion" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Condición</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($condiciones as $condicion)
                            <tr role="row" data-id="{{ $condicion->condicion_id }}">
                                <td>{{ $condicion->condicion_id }}</td>
                                <td>{{ $condicion->condicion_name }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Condición</th>
                                <th>Opciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('geren.condicion.modaladd')
@include('geren.condicion.modaledit')
@include('geren.condicion.modaldelete')
@include('geren.condicion.modalerror')

@endsection