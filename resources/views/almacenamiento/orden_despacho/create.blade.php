@extends('app')

@section('htmlheader_title')
    Despacho
@endsection

@section('contentheader_title')
    Orden de Despacho
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
	var table, table_detail, arr_detail, product_id;
	var cajas_disp, kilos_disp, total_cajas, total_kilos, table_data;

	$(document).ready(function(){

		var input = "{!! \Request::input('ok') !!}";
		if( input == "true" )
		{
			$(".alert-success").html("El despacho fue creado exitosamente.");
			$(".alert-success").show();
			$(".alert-danger").hide();
		}
		else
		{
			$(".alert").hide();
		}
		
		$('.datepicker').datepicker({
	        format : 'dd-mm-yyyy',
	        autoclose: true,
	        language : 'es'
	    });

	    $('.datepicker').datepicker('update', new Date());

	    $("#save").click(function(e){

            e.preventDefault();

            var form = $("#form-add");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            table_data = table_detail.rows().data();
            var arr_detail = [];
            for (var i = 0; i < table_data.length; i++) {

            	arr_detail.push(table_data[i]);
            };

            var data = form.serialize()+'&detail='+arr_detail;

            $.post(url, data, function(resp)
            {
                if(resp['estado'] == "ok")
                {
                	var input = "{!! \Request::input('ok') !!}";
					if( input == "true" )
					{
						location.reload();
					}
					else
					{
						window.location.href = location.href+'?ok=true';
					}
                }
                else
                	alert('Ha ocurrido un error. Inténtalo más tarde.');

            }).fail(function(resp){
                $('body').animate({ scrollTop: 0 }, 'slow');
                var html = "<strong>Ops!</strong> Ha ocurrido un error.<br><br><ul>";
                for(var key in resp.responseJSON)
                {
                    html += "<li>" + resp.responseJSON[key][0] + "</li>";
                }
                html += "</ul>";
                $(".alert-success").hide();
                $(".alert-danger").html(html).show();
            });
        });

		$("#lote_search").click(function(){

			$('.alert').hide();

			if(table != undefined)
			{
				table.destroy();
			}

			table = $('#table-lotes').DataTable({
		        "ajax" : "../lote?q=despacho",
		        "language": {
		            "url": "../../plugins/datatables/es_ES.txt"
		        },
		        "order": [[ 1, 'desc' ]],
		        "columnDefs": [
					{ "visible": false, "targets": 0 }
				],
		        'fnCreatedRow': function (nRow, aData, iDataIndex) {
		            $(nRow).attr('data-id', aData[1]);
		            $(nRow).attr('data-pid', aData[2]);
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

	    		$("#n_lote").text('0');
				$("#n_cajas").text('0');
				$("#total_peso_real").text('0');

	    		$.get("../lote/show?q=despacho",
	    			{lote_id : lote_id},
	    			function(data){
				        
	    				$("#modal_lote").modal('hide');
			    		$("#lote_id").val(data.lote_id);
			    		var html_select = '';
	    				for (var key in data.lote_productos){
				            html_select += '<option value="'+key+'">'+
				            				data.lote_productos[key]+
				                    		'</option>';
				        }
				        $("#select_productos").empty();
				        $("#select_productos").html(html_select);

						$('.select2').select2().select2("val", null);

	    			});
	    	}
	    	else
	    	{
	    		alert("Debes seleccionar un LOTE");
	    	}
	    });

		$('#select_productos').on("select2:select", function() {
			if($("#select_productos").select2('val') != "")
			{
				lote_id = $("#lote_id").val();
				producto_id = $("#select_productos").select2('val');

				$.get('../caja/lote_product',
						{lote_id : lote_id,
							producto_id : producto_id},
						function(data){
							cajas_disp = data.n_cajas;
							kilos_disp = data.kilos;
							$("#n_lote").text(lote_id);
							$("#n_cajas").text(cajas_disp);
							$("#total_peso_real").text(kilos_disp);
						});
			}
		});

	    table_detail = $('#table-detail').DataTable({
            "language": {
                "url": "../../plugins/datatables/es_ES.txt"
            },
            "order": [[ 1, 'desc' ]],
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<a class='btn btn-xs btn-danger' id='delete_detail'><i class='fa fa-close'></i></a>"
            },
            {
                "targets": [ 1 ],
                "visible": false
            }],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $(nRow).attr('data-id', aData[0]);
            }
        });

        $('#table-detail tbody').on('click', '#delete_detail', function () {
            product_id = $(this).parents('tr').data('id');

            table_detail.row( $(this).parents('tr') )
                .remove()
                .draw();
        });

	    $("#add-detail").click(function(){

	    	table_data = table_detail.rows().data();

	    	$(".alert-danger").hide();
	    	product_id = $("#select_productos").val();
	    	lote_id = $("#lote_id").val();
	    	cajas_plan = $("#cajas_plan").val();
	    	kilos_plan = $("#kilos_plan").val();

            if(product_id == undefined || lote_id == "" || 
            	cajas_plan == "" || kilos_plan == "")
            {
                $('body').animate({ scrollTop: 0 }, 'slow');
                var html = "<strong>Ops!</strong> Ha ocurrido un error.<br><br><ul>";
                html += "<li>Debes completar la información de ingreso.</li>";
                html += "</ul>";
                $(".alert-success").hide();
                $(".alert-danger").html(html).show();
            }
            else if(kilos_plan > kilos_disp || cajas_plan > cajas_disp)
            {
            	$('body').animate({ scrollTop: 0 }, 'slow');
                var html = "<strong>Ops!</strong> Ha ocurrido un error.<br><br><ul>";
                html += "<li>Has ingresado más KILOS y/o CAJAS de las existentes.</li>";
                html += "</ul>";
                $(".alert-success").hide();
                $(".alert-danger").html(html).show();
            }
            else if(kilos_plan == 0 || cajas_plan == 0)
            {
            	$('body').animate({ scrollTop: 0 }, 'slow');
                var html = "<strong>Ops!</strong> Ha ocurrido un error.<br><br><ul>";
                html += "<li>La cantidad de KILOS y/o CAJAS debe ser mayor a 0.</li>";
                html += "</ul>";
                $(".alert-success").hide();
                $(".alert-danger").html(html).show();
            }
            else
            {
            	producto = $("#select_productos option:selected").text();
            	producto_id = $("#select_productos").val();
            	in_table = false;
            	for (var i = 0; i < table_data.length; i++) {
            		if(table_data[i][0] == lote_id && table_data[i][1] == producto_id)
            		{
            			in_table = true;
            			break;
            		}
            	};

            	if(in_table)
            	{
            		$('body').animate({ scrollTop: 0 }, 'slow');
	                var html = "<strong>Ops!</strong> Ha ocurrido un error.<br><br><ul>";
	                html += "<li>Ya fue ingresado un detalle de este LOTE y este PRODUCTO.</li>";
	                html += "</ul>";
	                $(".alert-success").hide();
	                $(".alert-danger").html(html).show();
            	}
            	else
            	{
            		table_detail.row.add( [
	                    lote_id,
	                    producto_id,
	                    producto,
	                    cajas_plan,
	                    kilos_plan
	                ] ).draw( false );

	            	$("#cajas_plan").val("");
	            	$("#kilos_plan").val("");

	                total_cajas = table_detail.column(3).data()
		            	.reduce( function (a, b) {
		                    return parseFloat(a) + parseFloat(b);
		                }, 0 );
	            	total_kilos = table_detail.column(4).data()
	            		.reduce( function (a, b) {
		                    return parseFloat(a) + parseFloat(b);
		                }, 0 );

	                $(table_detail.column(3).footer()).html(total_cajas);
	                $(table_detail.column(4).footer()).html(total_kilos);
            	}
            	
            }
	    });
	});

</script>
<div class="row">
	<div class="col-md-12">
	    <div class="box box-primary">
	        <div class="box-header with-border">
	            <h3 class="box-title">Despacho N° {{ $proximo_despacho }}</h3>
	            <br>
	            <p class="alert alert-success">
	            </p>
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
	        	{!! Form::open(['url' => 'almacenamiento/despacho/store',
                  'method' => 'POST',
                  'id' => 'form-add']) !!}

	        	@include('almacenamiento.orden_despacho.fields')

	        	{!! Form::close() !!}
	        </div>
	        <div class="box-footer" style="text-align:center">
	            <a id="save" class="btn btn-lg btn-primary">Crear Despacho</a>
	        </div>
	    </div>
	</div>
</div>

@include('almacenamiento.orden_despacho.modallotes')

@endsection