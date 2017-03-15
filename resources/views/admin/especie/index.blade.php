@extends('app')

@section('htmlheader_title')
    Especie
@endsection

@section('contentheader_title')
    Lista de Especies
@endsection

@section('main-content')

    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
    <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">

        var table;

        $(document).ready(function(){

            alert = new Alert('#notifications');

            table = $('#table-especies').DataTable({
                "ajax" : "especie",
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
                        alert.success("La información fue ingresada exitosamente.");
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

            $('#table-especies tbody').on( 'click', '#edit', function () {
                especie_id = $(this).parents('tr').data('id');

                $.get("especie/edit",
                        {especie_id : especie_id},
                        function(data){
                            setValues(data, 0);
                            $('#modal_edit').modal('show');
                        });
            } );

            $('#table-especies tbody').on( 'click', '#delete', function () {
                especie_id = $(this).parents('tr').data('id');

                $.get("especie/edit",
                        {especie_id : especie_id},
                        function(data){
                            setValues(data, 1);
                            $('#modal_delete').modal('show');

                            $("#form-delete :input")
                                    .not('.btn')
                                    .not("input[type='hidden']")
                                    .attr("disabled", true);
                        });
            } );
        });

        function setValues(data, n)
        {
            var form = "form-edit";
            if (n == 1) {
                form = "form-delete";
            }

            $("#"+form+" input[name='especie_id']").val(data.especie_id);
            $("#"+form+" input[name='especie_name']").val(data.especie_name);
            $("#"+form+" input[name='especie_comercial_name']").val(data.especie_comercial_name);
            $("#"+form+" input[name='especie_abbreviation']").val(data.especie_abbreviation);
        }

    </script>

    <div class="container">
        <div class="row">
            <div class="col-md-11">
                <div class="box box-primary">
                    <div class="box-body">
                        <a class="btn btn-primary" id="add">
                            <i class="fa fa-plus"></i>
                            Agregar Especie
                        </a>
                        <br><br>
                        <table class="table table-bordered" id="table-especies" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Nombre Comercial</th>
                                <th>Abreviatura</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Nombre Comercial</th>
                                <th>Abreviatura</th>
                                <th>Opciones</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.especie.modaladd')
    @include('admin.especie.modaledit')
    @include('admin.especie.modaldelete')

@endsection