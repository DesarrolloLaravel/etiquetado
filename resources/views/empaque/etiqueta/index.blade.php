@extends('app')

@section('htmlheader_title')
    Etiquetas
@endsection

@section('contentheader_title')
    Lista de Etiquetas
@endsection

@section('main-content')

<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">

	var table;

    $(document).ready(function(){

        $.ajaxSetup({
            headers:{
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".alert").hide();

        table = $('#table-etiquetas').DataTable({
            "ajax" : "etiqueta",
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "order": [[ 0, 'desc' ]],
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-xs btn-warning' id='reprint'><i class='fa fa-file-text'></i></button>\
                    <button class='btn btn-xs btn-danger' id='delete'><i class='fa fa-close'></i></button>"
            }],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('data-id', aData[0]);
            }
        });

        $('#table-etiquetas tbody').on( 'click', '#reprint', function (){

            $(".alert").hide();

            etiqueta_id = $(this).parents('tr').data('id');

            $.get('etiqueta/reprint',
                {etiqueta_id : etiqueta_id},
                function(data){
                    console.log(data[1]);
                    if(data[0] == "ok")
                    {
                        var printPage = window.open('{{ url("empaque/etiqueta/print") }}'+'/'+etiqueta_id, '');
                        printPage.print();
                    }
                    else
                    {
                        $(".alert-danger").html(data[1]).show();
                    }
                }).fail(function(resp){
                    alert("Ha ocurrido un error. Inténtalo más tarde.")
                });

            
        });

        $('#table-etiquetas tbody').on( 'click', '#delete', function (){

            etiqueta_id = $(this).parents('tr').data('id');
            etiqueta_barcode = $(this).parents('tr').find('td:eq(3)').text();

            $('#modal_cancel').modal('show');

            $("#form-delete input[name='etiqueta_id']").val(etiqueta_id);
            $("#form-delete #etiqueta_barcode").val(etiqueta_barcode);
        });

        $("#recepcion2").click(function(){
            $(".alert").hide();

            $("#modal_recepcion").modal('show');
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
                    $(".alert-success").html("La etiqueta fue recepcionada exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-edit').trigger("reset");

                    $('#modal_recepcion').modal('hide');

                    table.ajax.reload();
                }
                else
                {
                    $(".alert-danger").html(resp[1]).show();
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

            $("#etiqueta_barcode")
            .attr("disabled", false);

            var form = $("#form-delete");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            var data = form.serialize();

            $("#etiqueta_barcode")
            .attr("disabled", true);

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("La etiqueta fue anulada exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-delete').trigger("reset");

                    $('#modal_cancel').modal('hide');

                    table.ajax.reload();
                }
                else
                {
                    $(".alert-danger").html(resp[1]).show();
                }

            }).fail(function(resp){

                html = "";
                for(var key in resp.responseJSON)
                {
                    html += resp.responseJSON[key][0] + "<br>";
                }
                $(".alert-success").hide()
                $(".alert-danger").html(html).show();
            });
        });

    });

</script>
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="recepcion2">
                        <i class="fa fa-check"></i>
                        Recepcionar Etiqueta
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <p class="alert alert-danger"></p>
                    <table class="table table-bordered" id="table-etiquetas" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lote</th>
                                <th>Caja</th>
                                <th>C&oacute;digo</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($etiquetas as $etiqueta)
                            <tr role="row" data-id="{{ $etiqueta->etiqueta_id }}">
                                <td>{{ $etiqueta->etiqueta_id }}</td>
                                <td>{{ $etiqueta->caja->orden->lote->lote_id }}</td>
                                <td>{{ $etiqueta->caja->caja_number }}</td>
                                <td>{{ $etiqueta->etiqueta_barcode }}</td>
                                <td>{{ $etiqueta->etiqueta_estado }}</td>
                                <td>{{ $etiqueta->etiqueta_fecha }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Lote</th>
                                <th>Caja</th>
                                <th>C&oacute;digo</th>
                                <th>Estado</th>
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

@include('empaque.etiqueta.modalrecepcion')
@include('empaque.etiqueta.modalcancel')

@endsection