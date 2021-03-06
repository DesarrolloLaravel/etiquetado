@extends('app')

@section('htmlheader_title')
    Etiquetas
@endsection

@section('contentheader_title')
    Imprimir Etiqueta
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">
<script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datepicker/locales/bootstrap-datepicker.es.js') }}" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/jquery.dataTables.min.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">

var table, table2;
var lote_id;
	
$(document).ready(function(){

	lote_id = $("#lote_id").val();

	$('.alert').hide();
	$('.select2').select2();

	$('.datepicker').datepicker({
        format : 'dd-mm-yyyy',
        autoclose: true,
        language : 'es'
    });


    $('.datepicker').datepicker('update', new Date());

    $("#refresh").click(function(){
    	if($("#lote_id").val() == "")
    	{
    		alert("Debes elegir un Lote.")
    	}
    	else
    	{
    		$.get("../lote/show",
				{lote_id : lote_id},
				function(data){
		    		$("#caja_number").val(data.caja_number);
			}).fail(function(resp){
				alert("Ha ocurrido un error. Inténtalo más tarde.");
			});
    	}
    });

	$("#print").click(function(){

		$('.alert').hide();



		var form = $("#form-add");
        //obtengo url
        var url = form.attr('action');

        var disabled = form.find(':input:disabled').removeAttr('disabled');
        //obtengo la informacion del formulario
        var data = form.serialize();

        disabled.attr('disabled','disabled');

        console.log(data);

		$.post(url, data,
			function(data){
				if(data['estado'] == "ok")
				{	
					/*$.get("print/"+data['etiqueta_id'],function(data){
						var win = window.open('', '_blank');
    					win.location.href = data;
					});*/
					var printPage = window.open('{{ url("empaque/etiqueta_mp/print") }}'+'/'+data['etiqueta_mp_id'], '');
					//printPage.close();
					//printPage.print({bUI: false, bSilent: true,bShrinkToFit: true});
					//$("#caja_number").val(parseInt($("#caja_number").val())+1);


					$('#cajas').val("");
					$("#peso_real").val("");
				}
				else
				{
					alert("Ha ocurrido un error. Inténtalo más tarde.");
				}
			
		}).fail(function(resp){

            html = "";
            for(var key in resp.responseJSON)
            {
                html += resp.responseJSON[key][0] + "<br>";
            }

            $(".alert-success").hide()
            $(".alert-danger").html(html).show();

            alert.error(html);
        });
	});


	$("#lote_search").click(function(){

		$('.alert').hide();

		if(table != undefined)
		{
			table.destroy();
		}

		table = $('#table-lotes').DataTable({
	        "ajax" : "../lote?q=etiquetamp",
	        "language": {
	            "url": "../../plugins/datatables/es_ES.txt"
	        },
	        "order": [[ 1, 'desc' ]],
	        "columnDefs": [
				{ "visible": false, "targets": 0 }
			],
	        'fnCreatedRow': function (nRow, aData, iDataIndex) {
	            $(nRow).attr('data-id', aData[1]);
	            $(nRow).attr('data-especie_id', aData[8]);
	            $(nRow).attr('data-especie', aData[9]);

	        }
	    });

		$("#modal_lote").modal('show');
	});

	$('#table-lotes tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );


    $('#select_lote').click( function () {

    	if(table.rows('tr.selected').data().length > 0)
    	{
    		lote_id = $("#table-lotes").find('tbody tr.selected').data('id');
    		especie_id = $("#table-lotes").find('tbody tr.selected').data('especie_id');
    		especie = $("#table-lotes").find('tbody tr.selected').data('especie');

    		$.get("../lote/show?q=etiquetamp",
    			{lote_id : lote_id},
    			function(data){


    				$("#modal_lote").modal('hide');
		    		$("#lote_id").val(data.lote_id);
		    		$("#especie").val(especie)
			        $("#select_productos").html("");
			        $("#peso_estandar").val("");
    				$("#producto_detail").val("");

    			});

             
            $.get('../ordenproduccion/cargar_producto',
            	{especie_id:especie_id},
            	function(data){
                $.each(data, function(key, element) {
                    $('#producto_ide').append("<option value='" + key +"'>" + element + "</option>");
                });
            });


    	}
    	else
    	{
    		alert("Debes seleccionar un LOTE");
    	}
    });

  });

</script>

<div class="row" id="modal_create">
	<div class="col-md-12">
	    <div class="box box-primary">
	        <div class="box-header with-border">
	            <h3 class="box-title">Ingreso de datos</h3>
	            <br>
	            <p class="alert alert-success"></p>
	            <div class="alert alert-danger">
	                <strong>Ops!</strong> Ha ocurrido un error.<br><br>
	                <ul>
	                    @foreach ($errors->all() as $error)
	                        <li>{{ $error }}</li>
	                    @endforeach
	                </ul>
	            </div>
	        </div>
	        <div class="box-body">
	        	{!! Form::open(['url' => 'empaque/etiqueta_mp/store',
                  'method' => 'POST',
                  'id' => 'form-add']) !!}

	        	@include('empaque.etiqueta_mp.fields')

	        	{!! Form::close() !!}
	        	
	        </div>
	        <div class="box-footer" style="text-align:center">
	            <a id="print" class="btn btn-lg btn-primary">Imprimir</a>
	        </div>
	    </div>
	</div>
</div>
@include('empaque.etiqueta_mp.modallotes')

@endsection