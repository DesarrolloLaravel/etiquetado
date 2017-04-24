@extends('app')

@section('htmlheader_title')
    Lotes
@endsection

@section('contentheader_title')
    Lista de Lotes
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">
<script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datepicker/locales/bootstrap-datepicker.es.js') }}" type="text/javascript"></script>
<style type="text/css">
    td.details-control {
    background: url("../../img/details_open.png") no-repeat center center;
    cursor: pointer;
    width: 30px;
    }
    tr.shown td.details-control {
        background: url("../../img/details_close.png") no-repeat center center;
        width: 30px;
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

    $(document).ready(function(){

        $.ajaxSetup({
            headers:{
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".alert").hide();

        table = $('#table-lotes').DataTable({
            "ajax" : "lote",
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-xs btn-primary' id='edit'><i class='fa fa-pencil'></i></button>\
                    <button class='btn btn-xs btn-danger' id='delete'><i class='fa fa-close'></i></button>"
            },
            {
                    "targets" : 0,
                    "visible" : false
                }],
            "order": [[ 1, 'desc' ]],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).find('td:first').addClass('details-control');
                $(nRow).attr('data-id', aData[1]);
            }
        });

        $('#table-lotes tbody').on('click', 'td.details-control', function () {

            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } 
            else {
                // Open this row
                lote_id = $(this).parents('tr').data('id');
                $.get("lote/show",
                    {lote_id : lote_id},
                    function(data)
                    {
                        var arr = [];
                        arr['Fecha Guía/Factura'] = data.lote_fecha_documento;
                        arr['Fecha Ingreso Planta'] = data.lote_fecha_planta;
                        arr['Kilos Recibidos'] = data.lote_kilos_recepcion;
                        arr['Kilos Declarados'] = data.lote_kilos_declarado;
                        arr['Cajas Recibidas'] = data.lote_cajas_recepcion;
                        arr['Cajas Declaradas'] = data.lote_cajas_declarado;
                        arr['Declaración Jurada'] = data.lote_djurada;
                        arr['Reestricción'] = data.lote_reestriccion;
                        arr['Tipo Lote'] = data.lote_tipo;
                        arr['Observaciones'] = data.lote_observaciones;

                        row.child([format(arr)]).show();
                        tr.addClass('shown');

                    }).fail(function()
                    {
                        alert("Ocurrió un error. Inténtalo más tarde.");
                    });
            }
        });

        $('#table-lotes tbody').on( 'click', '#edit', function ()
        {
            $(".alert").hide();

            lote_id = $(this).parents('tr').data('id');

            $.get("lote/edit",
                {lote_id : lote_id},
                function(data){
                    if(data['estado'] == "ok")
                    {
                        $('#modal_edit .modal-dialog .modal-content .modal-body').find('#form-edit').html(data['section']);

                        setValues(data['lote'], 0);

                        $('.datepicker').datepicker({
                            format : 'dd-mm-yyyy',
                            autoclose: true,
                            language : 'es'
                        });

                        $('#modal_edit').modal('show');
                    }

                });
        } );

        $('#table-lotes tbody').on( 'click', '#delete', function ()
        {
            $(".alert").hide();

            lote_id = $(this).parents('tr').data('id');

            $.get("lote/edit",
                {lote_id : lote_id},
                function(data){
                    if(data['estado'] == "ok")
                    {
                        $('#modal_delete .modal-dialog .modal-content .modal-body').find('#form-delete').html(data['section']);

                        setValues(data['lote'], 1);

                        $('#modal_delete').modal('show');

                        $("#form-delete :input")
                            .not('.btn')
                            .not("input[type='hidden']")
                            .attr("disabled", true);
                    }

                });
        } );

        $("#produccion").click(function(){

            $.get('lote/produccion',
                function(data){

                    $('#modal_produccion .modal-dialog .modal-content').find('.modal-body').html(data[1]);
                    $('#modal_produccion').modal('show');
                });
        });

        $(document).on('click','#update',function(event){

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

        $(document).on('click','#delete',function(event){

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
                    $(".alert-success").html("El lote fue cerrado exitosamente").show();
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

        $(document).on('click','#pro_lote',function(event){

            if($("#select_no_produccion").val() != null)
            {
                var lote_id = $("#select_no_produccion").val();

                $.post('lote/change',
                {lote_id : lote_id,
                    action: 1},
                function(data)
                {
                    if(data[1]==1){

                        alert("LOTE sin Declaración Jurada. Restricción: SOLO MERCADO NACIONAL");
                            
                    }

                    table.ajax.reload();
                    $("#produccion").trigger('click');
                });
            }
            else
            {
            alert("Debes seleccionar un LOTE");
            }

        });

        $(document).on('click','#np_lote',function(event){

            if($("#select_produccion").val() != null)
            {
                var lote_id = $("#select_produccion").val();

                $.post('lote/change',
                {lote_id: lote_id,
                action: 0},
                function(data)
                {
                    table.ajax.reload();
                    $("#produccion").trigger('click');
                });
            }
            else
            {
            alert("Debes seleccionar un LOTE");
            }
        });

    });

    function setValues(data, n)
    {
        var form = "form-edit";
        if (n == 1) {
            form = "form-delete";
        }

        $("#"+form+" input[name='lote_id']").val(data.lote_id);
        $("#"+form+" select[name='lote_year']").val(data.lote_year);
        $("#"+form+" select[name='lote_tipo_id']").val(data.lote_tipo_id);
        $("#"+form+" input[name='lote_n_documento']").val(data.lote_n_documento);
        $("#"+form+" select[name='lote_djurada']").val(data.lote_djurada == "NO" ? 1 : 2);
        $("#"+form+" select[name='lote_productor_id']").val(data.lote_productor_id);
        $("#"+form+" select[name='lote_elaborador_id']").val(data.lote_elaborador_id);
        $("#"+form+" select[name='lote_procesador_id']").val(data.lote_procesador_id);
        $("#"+form+" select[name='lote_calidad_id']").val(data.lote_calidad_id);
        $("#"+form+" select[name='lote_especie_id']").val(data.lote_especie_id);
        $("#"+form+" select[name='lote_condicion']").val(data.lote_condicion == "CONGELADO"?1:2);
        $("#"+form+" select[name='lote_mp_id']").val(data.lote_mp_id);
        $("#"+form+" select[name='lote_destino_id']").val(data.lote_destino_id);
        $("#"+form+" select[name='lote_cliente_id']").val(data.lote_cliente_id);
        $("#"+form+" input[name='lote_kilos_declarado']").val(data.lote_kilos_declarado);
        $("#"+form+" input[name='lote_kilos_recepcion']").val(data.lote_kilos_recepcion);
        $("#"+form+" input[name='lote_cajas_declarado']").val(data.lote_cajas_declarado);
        $("#"+form+" input[name='lote_cajas_recepcion']").val(data.lote_cajas_recepcion);
        $("#"+form+" input[name='lote_cajas_recepcion']").val(data.lote_cajas_recepcion);
        $("#"+form+" select[name='lote_reestriccion']").val(data.lote_reestriccion == "NO"?1:2);
        $("#"+form+" textarea[name='lote_observaciones']").text(data.lote_observaciones);
    }

</script>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="produccion">
                        <i class="fa fa-plus"></i>
                        Lote a Producci&oacute;n
                    </a>
                    <br><br>
                    <a class="btn btn-default disabled" id="activos">
                        Activos
                    </a>
                    <a class="btn btn-primary" href="{{ url('/admin/anulados') }}" id="anulados">
                        Anulados
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <table class="table table-bordered" id="table-lotes" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>N° Gu&iacute;a/Factura</th>
                                <th>Procesador</th>
                                <th>Productor</th>
                                <th>D. Jurada</th>
                                <th>Producci&oacute;n</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--@foreach ($lotes as $lote)
                            <tr role="row" data-id="{{ $lote->lote_id }}">
                                <td></td>
                                <td>{{ $lote->lote_id }}</td>
                                <td>{{ $lote->lote_n_documento }}</td>
                                <td>{{ $lote->procesador->procesador_name }}</td>
                                <td>{{ $lote->productor->productor_name }}</td>
                                <td>{{ $lote->lote_djurada }}</td>
                                <td>{{ $lote->lote_produccion }}</td>
                                <td></td>
                            </tr>
                            @endforeach--}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>N&uacute;mero</th>
                                <th>N° Gu&iacute;a/Factura</th>
                                <th>Procesador</th>
                                <th>Productor</th>
                                <th>D. Jurada</th>
                                <th>Producci&oacute;n</th>
                                <th>Opciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@include('admin.lote.produccion')
@include('admin.lote.modaledit')
@include('admin.lote.modaldelete')

@endsection