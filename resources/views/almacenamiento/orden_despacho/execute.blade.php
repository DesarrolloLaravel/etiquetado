@extends('app')

@section('htmlheader_title')
    Despachar
@endsection

@section('contentheader_title')
    Despachar
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/jquery.dataTables.min.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">

    var table, table_detail, orden_id, n_cajas_parcial = 0;
    var pressed = false; 
    var chars = [];
    var barcode = '';
    
    $(document).ready(function(){

        var input = "{!! \Request::input('ok') !!}";
        if( input == "true" )
        {
            $(".box-body:first .alert-success").html("La Orden fue despachada exitosamente.");
            $(".box-body:first .alert-success").show();
            $(".alert-danger").hide();
        }
        else
        {
            $(".alert").hide();
        }

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
                        $("#etiqueta_barcode").val(barcode);
                        $("#show").trigger('click');
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

        $("#discount").click(function(){
            $(".alert").hide();
            $("#modal_despachar").modal('show');
        });

        $("#save_detail").click(function(){

            table_data = table_detail.rows().data();

            if(table_data.length == 0)
            {
                alert("Debes agregar al menos una caja");
            }
            else
            {
                var arr_detail = [];
                for (var i = 0; i < table_data.length; i++) {

                    arr_detail.push(table_data[i]);
                };

                $.post('update',
                    {orden_id : orden_id,
                    arr_detail : arr_detail},
                    function(data){
                        if(data['estado'] == "ok")
                        {
                            $("#n_cajas_parcial").text('-');
                            $(".alert-success").html("La información fue guardada exitosamente.").show();
                            $(".alert-danger").hide();
                        }
                        else
                            alert('Ha ocurrido un error. Inténtalo más tarde.');
                    });
            }
            
        });

        $("#show").click(function(){

            $("#alert-danger-modal").hide();
            var form = $("#form-show");
            //obtengo url
            var url = form.attr('action')+"?q=despacho";
            //obtengo la informacion del formulario
            var data = form.serialize()+"&orden_id="+orden_id;

            table_data = table_detail.rows().data();

            $.get(url, data, function(resp)
            {
                if(resp['estado'] == "ok")
                {
                    in_table = false;
                    for (var i = 0; i < table_data.length; i++) {
                        if(table_data[i][1] == $("#etiqueta_barcode").val())
                        {
                            in_table = true;
                            break;
                        }
                    };

                    if(in_table)
                    {
                        var html = "<strong>Ops!</strong> Ha ocurrido un error.<br><br><ul>";
                        html += "<li>Esta caja ya fue ingresada al detalle.</li>";
                        html += "</ul>";
                        $(".alert-success").hide();
                        $(".alert-danger").html(html).show();
                    }
                    else
                    {
                        $(".alert-success").html("La etiqueta "+$("#etiqueta_barcode").val()+" fue agregada exitosamente").show();
                        $(".alert-danger").hide();
            
                        table_detail.row.add( [
                            resp['caja'].caja_id,
                            $("#etiqueta_barcode").val(),
                            resp['caja'].caja_peso_real
                        ] ).draw( false );

                        total_kilos = table_detail.column(2).data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );

                        $(table_detail.column(2).footer()).html(total_kilos);

                        n_cajas_parcial = n_cajas_parcial + 1;
                        $("#n_cajas_parcial").text(n_cajas_parcial);
                        $("#total_cajas_parcial").text(table_detail.rows().data().length);
                        $("#total_kilos_parcial").text(total_kilos);

                        $("#etiqueta_barcode").val("");
                    }

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
        });

        table_detail = $('#table-orden-detail').DataTable({
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<a class='btn btn-xs btn-danger' id='delete_detail'><i class='fa fa-close'></i></a>"
            }],
            "order": [[ 0, 'desc' ]],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('data-id', aData[0]);
            }
        });

        $('#table-orden-detail tbody').on('click', '#delete_detail', function () {
            product_id = $(this).parents('tr').data('id');

            $(".alert").hide();

            table_detail.row( $(this).parents('tr') )
                .remove()
                .draw();

            if(n_cajas_parcial > 0)
            {
                n_cajas_parcial = n_cajas_parcial - 1;
                $("#n_cajas_parcial").text(n_cajas_parcial);
            }

            total_kilos = table_detail.column(2).data()
                        .reduce( function (a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0 );

            $(table_detail.column(2).footer()).html(total_kilos);
            
            $("#total_cajas_parcial").text(table_detail.rows().data().length);
            $("#total_kilos_parcial").text(total_kilos);
            
        });

        $("#orden_search").click(function(){

            $(".alert").hide();

            if(table != undefined)
            {
                table.destroy();
            }

            table = $('#table-ordenes').DataTable({
                "ajax" : "../despacho?q=despacho",
                "language": {
                    "url": "../../plugins/datatables/es_ES.txt"
                },
                "order": [[ 0, 'desc' ]],
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData[0]);
                }
            });

            $("#modal_orden").modal('show');

        });

        $('#table-ordenes tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );

        $("#select_orden").click(function(){

            if(table.rows('tr.selected').data().length > 0)
            {
                orden_id = $("#table-ordenes").find('tbody tr.selected').data('id');

                $.get("../despacho/show",
                    {orden_id : orden_id},
                    function(data){

                        $("#orden_number").val(data.orden_id);
                        $("#orden_id").text(data.orden_id);
                        $("#orden_estado").text(data.orden_estado);
                        $("#orden_cliente").text(data.orden_cliente);
                        $("#orden_guia").text(data.orden_guia);
                        $("#orden_fecha").text(data.orden_fecha);

                        $("#detail tbody").empty();
                        var html = "";
                        for (var i = 0; i < data.orden_detalle.length; i++) {
                            html += "<tr>";
                            html += "<td>"+data.orden_detalle[i].lote_id+"</td>";
                            html += "<td>"+data.orden_detalle[i].producto+"</td>";
                            html += "<td>"+data.orden_detalle[i].cajas_plan+"</td>";
                            html += "<td>"+data.orden_detalle[i].kilos_plan+"</td>";
                            html += "</tr>";
                        };

                        $("#detail tbody").append(html);

                        $("#total_cajas").text(data.orden_total_cajas);
                        $("#total_kilos").text(data.orden_total_kilos);

                        table_detail.clear().draw();
                        for (var i = 0; i < data.orden_cajas.length; i++) {
                            
                            table_detail.row.add( [
                                data.orden_cajas[i]['id'],
                                data.orden_cajas[i]['codigo'],
                                data.orden_cajas[i]['kilos']
                            ] ).draw( false );  
                        };

                        total_kilos = table_detail.column(2).data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );

                        $(table_detail.column(2).footer()).html(total_kilos);

                        $("#n_cajas_parcial").text('-');
                        $("#total_cajas_parcial").text(table_detail.rows().data().length);
                        $("#total_kilos_parcial").text(total_kilos);

                        $("#modal_orden").modal('hide');

                    });
            }
            else
            {
                alert("Debes seleccionar una ORDEN");
            }
        });

        $("#add").click(function(){
            if($("#orden_number").val() == "")
            {
                alert("Debes seleccionar una Orden de Despacho");
            }
            else
            {
                $("#modal_despacho").modal('show');
            }
        });
    });

</script>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <p class="alert alert-success"></p>
                    {!! Form::open([
                        'method' => 'POST',
                        'id' => 'form-add']) !!}

                    @include('almacenamiento.orden_despacho.execute_fields')

                    {!! Form::close() !!}
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">

                    @include('almacenamiento.orden_despacho.execute_table')

                </div>
            </div>
        </div>
    </div>

@include('almacenamiento.orden_despacho.modal_orden_despacho')
@include('almacenamiento.orden_despacho.modal_despacho')

@if(\Auth::user()->users_role == "administracion")
    @include('almacenamiento.orden_despacho.modal_despachar')
@endif

@endsection