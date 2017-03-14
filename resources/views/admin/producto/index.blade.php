@extends('app')

@section('htmlheader_title')
    Productos
@endsection

@section('contentheader_title')
    Lista de Productos
@endsection

@section('main-content')

<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">

	var table;

	$(document).ready(function(){

        alert = new Alert('#notifications');

        $('.select2').select2();

	    table = $('#table-productos').DataTable({
	        "ajax" : "producto",
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
                    alert.success("El registro fue guardado exitosamente");
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
	});

</script>

<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="box box-primary">
                <div class="box-body">
                    <a class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i>
                        Agregar Producto
                    </a>
                    <br><br>
                    <table class="table table-bordered" id="table-productos" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Peso Est&aacute;ndar</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                            <tr role="row" data-id="{{ $producto->producto_id }}">
                                <td>{{ $producto->producto_id }}</td>
                                <td>{{ $producto->producto_nombre }}</td>
                                <td>{{ $producto->producto_peso }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Peso Est&aacute;ndar</th>
                                <th>Opciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.producto.modaladd')

@endsection