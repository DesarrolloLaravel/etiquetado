@extends('app')

@section('htmlheader_title')
    Etiquetas Materia Prima
@endsection

@section('contentheader_title')
    Lista de Etiquetas MP
@endsection

@section('main-content')

<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">

    var table, barcode = '';
    var pressed = false; 
    var chars = [];
    var frigorifico_id, camara_id;
    var etiqueta_mp_id;

    $(document).ready(function(){

        $(window).keypress(function(e) {
            
            if (e.which >= 48 && e.which <= 102) {
                chars.push(String.fromCharCode(e.which));
            }

            if ( e.which === 13 ) {
                e.preventDefault();
            }

            console.log(e.which + ":" + chars.join("|"));
            if (pressed == false) {
                pressed = true;
                t = setTimeout(function(){
                    if (chars.length >= 10) {
                        var barcode = chars.join("");
                        console.log("Barcode Scanned: " + barcode);
                        // assign value to some input (or do whatever you want)
                        $("#etiqueta_mp_barcode").val(barcode);
                        $("#update").trigger('click');
                    }
                    clearTimeout(t);
                    chars = [];
                    pressed = false;
                },350);
            }
            
        });

        $.ajaxSetup({
            headers:{
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".alert").hide();

        table = $('#table-etiquetas-mp').DataTable({
            "ajax" : "etiqueta_mp",
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "order": [[ 2, 'desc' ]],
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-xs btn-warning' id='reprint'><i class='fa fa-file-text'></i></button>\
                    <button class='btn btn-xs btn-danger' id='delete'><i class='fa fa-close'></i></button>"
            },
                {
                    "targets" : -1,
                    "visible" : false
                }],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('data-id', aData[0]);

            }

        });

        $("#reprint_final").click(function(){

            var idioma = $("#idioma").val();

            console.log(idioma);

            $.get('etiqueta/reprint',
                {etiqueta_id : etiqueta_id},
                function(data){
                    if(data[0] == "ok")
                    {
                        $("#modal_idioma").modal('hide');
                        var printPage = window.open('{{ url("geren/etiqueta_mp/print") }}'+'/'+etiqueta__id+'/'+idioma, '');
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

        $('#table-etiquetas-mp tbody').on( 'click', '#reprint', function (){

            $(".alert").hide();

            etiqueta_mp_id = $(this).parents('tr').data('id');



        });

        $('#table-etiquetas-mp tbody').on( 'click', '#delete', function (){

            etiqueta_mp_id = $(this).parents('tr').data('id');
            etiqueta_mp_barcode = $(this).parents('tr').find('td:eq(2)').text();

            $('#modal_cancel').modal('show');

            $("#form-delete input[name='etiqueta_mp_id']").val(etiqueta_mp_id);
            $("#form-delete #etiqueta_mp_barcode").val(etiqueta_mp_barcode);
        });

        $("#recepcion2").click(function(){
            $(".alert").hide();
            
            $("#modal_recepcion").modal('show');
            $("#etiqueta_barcode").focus();

            $.get('frigorifico?q=select', function(data){
                $('#select_frigorifico').empty();
                $.each(data, function(key, element) {
                    $('#select_frigorifico').append("<option value='" + key + "'>" + element + "</option>");
                });
            });
        });

        $("#select_frigorifico").click(function(){

            if($("#select_frigorifico").val() == "")
            {
                alert("Ha ocurrido un error. Inténtalo más tarde.");
            }
            else
            {
                frigorifico_id = $("#select_frigorifico").val();
                camara_id = undefined;
                posicion_id = undefined;
                $.get('camara?q=select',
                    {frigorifico_id : frigorifico_id},
                    function(data){
                        $('#select_camara').empty();
                        $.each(data, function(key, element) {
                            $('#select_camara').append("<option value='" + key + "'>" + element + "</option>");
                        });
                });
            }
        });

        $("#select_camara").click(function(){

            if($("#select_camara").val() == "")
            {
                alert("Ha ocurrido un error. Inténtalo más tarde.");
            }
            else
            {
                camara_id = $("#select_camara").val();
                
            }
        });


        $("#update").click(function(){

            if(frigorifico_id == undefined || 
                camara_id == undefined){

                    $("#alert-danger-modal").html("El PALLET no ha podido ser recepcionado debido a que falta información. Debes completar el formulario y volver a escanear el PALLET.").show();
                }
                else
                {
                    $("#alert-danger-modal").hide();
                    var form = $("#form-edit");
                    //obtengo url
                    var url = form.attr('action');
                    //obtengo la informacion del formulario
                    var data = form.serialize();

                    $.post(url, data, function(resp)
                    {
                        if(resp[0] == "ok")
                        {
                            $(".alert-success").html("El Pallet "+$("#etiqueta_mp_barcode").val()+" fue recepcionado exitosamente").show();
                            $(".alert-danger").hide();
                            //reseteo el formulario
                            $("#etiqueta_barcode").val("");
                            //$('#form-edit').trigger("reset");

                            //$('#modal_recepcion').modal('hide');

                            table.ajax.reload();
                            
                        }
                        else
                        {
                            $(".alert-success").hide();
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
                }
        });

        $("#delete").click(function(){

            $("#etiqueta_barcode")
            .attr("disabled", false);

            var form = $("#form-delete");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            var data = form.serialize();

            $("#etiqueta_barcode").attr("disabled", true);

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("La etiqueta fue anulada exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-delete').trigger("reset");

                    $('#modal_cancel').modal('hide');
                    $("#etiqueta_barcode").attr("disabled", false);

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
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="recepcion2">
                        <i class="fa fa-check"></i>
                        Recepcionar Pallet Frigorifico
                    </a>
                    <br><br>
                    <p class="alert alert-success"></p>
                    <p class="alert alert-danger"></p>
                    <table class="table table-bordered" id="table-etiquetas-mp" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lote</th>
                                <th>Producto</th>
                                <th>C&oacute;digo</th>
                                <th>Posici&oacute;n</th>
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--@foreach ($etiquetas_mp as $etiqueta_mp)
                            <tr role="row" data-id="{{ $etiqueta_mp->etiqueta_mp_id }}">
                                <td>{{ $etiqueta_mp->etiqueta_mp_id }}</td>
                                <td>{{ $etiqueta_mp->lote->lote_id }}</td>
                                <td>{{ $etiqueta_mp->producto }}</td>
                                <td>{{ $etiqueta_mp->etiqueta_mp_barcode }}</td>
                                <td>{{ $etiqueta_mp->etiqueta_mp_estado }}</td>
                                <td>{{ $etiqueta_mp->etiqueta_mp_fecha }}</td>
                                <td></td>
                            </tr>
                            @endforeach--}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Lote</th>
                                <th>Producto</th>
                                <th>C&oacute;digo</th>
                                <th>Posici&oacute;n</th>                                
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@include('geren.etiqueta_mp.modalrecepcion')
@include('geren.etiqueta_mp.modalcancel')

@endsection