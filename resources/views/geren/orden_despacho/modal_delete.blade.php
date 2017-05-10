<script type="text/javascript">

    $(document).ready(function(){

        $("#form-delete").submit(function(e){
            e.preventDefault();

            var form = $("#form-delete");
            //obtengo url
            var url = form.attr('action');

            $.post(url,{orden_id : orden_id},
                    function(data){
                        if(data['estado'] == "ok")
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
                        {
                            var html = "Ha ocurrido un error. Inténtalo más tarde.";
                            $(".alert-success").hide();
                            $(".alert-danger").html(data).show();
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
    });

</script>
<div class="modal fade" id="modal_delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">¿Está seguro que desea eliminar esta Orden de Despacho?</h4>
                <p class="alert alert-success" id="alert-success-modal"></p>
                <p class="alert alert-danger" id="alert-danger-modal"></p>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => 'geren/despacho/delete',
                    'class' => 'form-horizontal',
                    'method' => 'post',
                    'id' => 'form-delete']) !!}

                <div class="modal-footer" style="text-align:center">
                    <button id="ok_delete" type="submit" class="btn btn-lg btn-success">Eliminar</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->