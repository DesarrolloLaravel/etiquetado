<div class="modal fade" id="modal_prod">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Crear Informe de Producción</h4>
				<p class="alert alert-danger"></p>
			</div>
			<div class="modal-body">
				<div class="col-sm-4">
					<div class="form-group" >
						{!! Form::open(['url' => 'almacenamiento/caja/export/informe',
						'class' => 'form-horizontal',
						'method' => 'GET',
						'id' => 'form-fecha']) !!}

						{!! Form::label('fecha_etiqueta', 'Fecha Informe',
						['class' => 'control-label']) !!}
						{!! Form::text('fecha_id', null, ['class' => 'form-control datepicker', 'id' => 'fecha_id']) !!}

						{!! Form::close() !!}
					</div>
				</div>
				
				<div class="modal-footer" style="text-align:center">
					<button id="select_date" type="button" class="btn btn-lg btn-primary">OK</button>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
