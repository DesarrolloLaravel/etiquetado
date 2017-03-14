@extends('app')

@section('htmlheader_title')
    Lote
@endsection

@section('contentheader_title')
    Agregar Lote
@endsection

@section('main-content')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">
<script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datepicker/locales/bootstrap-datepicker.es.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    
    $(document).ready(function(){

        $('.alert').hide();

        $('.datepicker').datepicker({
            format : 'dd-mm-yyyy',
            autoclose: true,
            language : 'es'
        });

        //$('.datepicker').datepicker('update', new Date());

        $("#save").click(function(e){

            e.preventDefault();

            var form = $("#form-add");
            //obtengo url
            var url = form.attr('action');
            //obtengo la informacion del formulario
            var data = form.serialize();

            $.post(url, data, function(resp)
            {
                if(resp[0] == "ok")
                {
                    $(".alert-success").html("El Lote fue creado exitosamente").show();
                    $(".alert-danger").hide();
                    //reseteo el formulario
                    $('#form-add').trigger("reset");

                    $('.datepicker').datepicker('update', new Date());

                    $.get('next', function(data){
                        $("input[name='lote_number']").val(data);
                    });
                }

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

    });

</script>

<div class="row">
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
                {!! Form::open(['url' => 'admin/lote/store',
                    'method' => 'POST',
                    'id' => 'form-add']) !!}

                    @include('admin.lote.fields')
                    @yield('contentPanel')

                {!! Form::close() !!}
            </div>
            <div class="box-footer" style="text-align:center">
                <button id="save" class="btn btn-lg btn-primary">Crear Lote</button>
            </div>
        </div>
    </div>
</div>
@endsection