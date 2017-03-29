@extends('app')

@section('htmlheader_title')
    Calibres
@endsection

@section('contentheader_title')
    Lista de Calibres
@endsection

@section('main-content')

    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
    <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">

        var table;

        $(document).ready(function(){

            alert = new Alert('#notifications');

            table = $('#table-calibres').DataTable({
                "ajax" : "calibre",
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
                $('#form-add').trigger("reset");
                $(".alert").hide();
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

            $('#table-calibres tbody').on( 'click', '#edit', function () {
                calibre_id = $(this).parents('tr').data('id');

                $.get("calibre/edit",
                        {calibre_id : calibre_id},
                        function(data){

                            if(data[0] == "nok"){
                                
                                $('#modal_error').modal('show');
                                $(".alert").hide();
                            
                            }else{


                                $(".alert").hide();
                                $('#modal_edit .modal-dialog .modal-content .modal-body').find('#form-edit').html(data['section']);

                                setValues(data['calibre'], 0);

                                $('#modal_edit').modal('show');
                            }
                            
                        });
            } );

            $('#table-calibres tbody').on( 'click', '#delete', function () {

                calibre_id = $(this).parents('tr').data('id');

                $.get("calibre/edit",
                        {calibre_id : calibre_id},
                        function(data){

                            if(data[0] == "nok"){
                                
                                $('#modal_error').modal('show');
                                $(".alert").hide();
                            
                            }else{
                                

                                $(".alert").hide();
                                $('#modal_delete .modal-dialog .modal-content .modal-body').find('#form-delete').html(data['section']);

                                setValues(data['calibre'], 1);

                                $("#form-delete :input")
                                    .not('.btn')
                                    .not("input[type='hidden']")
                                    .attr("disabled", true);
                                $('#modal_delete').modal('show');
                            }

                        });
            } );
        });

        function setValues(data, n)
        {
            var form = "form-edit";
            if (n == 1) {
                form = "form-delete";
            }

            $("#"+form+" input[name='calibre_id']").val(data.calibre_id);
            $("#"+form+" input[name='calibre_nombre']").val(data.calibre_nombre);
            $("#"+form+" input[name='calibre_unidad_medida_id']").val(data.calibre_unidad_medida_id);
        }

    </script>

    <div class="container">
        <div class="row">
            <div class="col-md-11">
                <div class="box box-primary">
                    <div class="box-body">
                        <a class="btn btn-primary" id="add">
                            <i class="fa fa-plus"></i>
                            Agregar Calibre
                        </a>
                        <br><br>
                        <table class="table table-bordered" id="table-calibres" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Calibre</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Calibre</th>
                                <th>Opciones</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.calibre.modaladd')
@include('admin.calibre.modaledit')
@include('admin.calibre.modaldelete')
@include('admin.calibre.modalerror')
@endsection