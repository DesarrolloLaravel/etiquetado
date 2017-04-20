<div class="col-sm-12">
	<div class="col-sm-4">
		<div class="form-group">
				{!! Form::label('etiqueta_lote_id', 'Lote',
					['class' => 'control-label']) !!}
				<div class="input-group">
					<input name="lote_id" id="lote_id" type="text" class="form-control" disabled>
					<span class="input-group-btn">
						<button id="lote_search" class="btn btn-secondary" type="button">
							<i class="fa fa-search"></i>
						</button>
					</span>
				</div>
			</div>
		</div>
	<div class="col-sm-4">
		<div class="form-group">
			{!! Form::label('especie', 'Especie',
				['class' => 'control-label']) !!}
			{!! Form::text('especie', null, ['class' => 'form-control','id'=>'especie','disabled']) !!}
		</div>
	</div>	
	<div class="col-sm-4">
		<div class="form-group">
			{!! Form::label('fecha_etiqueta', 'Fecha Etiqueta',
				['class' => 'control-label']) !!}
			{!! Form::text('etiqueta_fecha', null, ['class' => 'form-control datepicker']) !!}
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="col-sm-4">
		<div class="form-group">
			{!! Form::label('producto', 'Producto',
				['class' => 'control-label']) !!}
			{!! Form::select('orden_productos_id',$productos,
								null,
								['class' => 'form-control','id' => 'producto_ide']) !!}
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			{!! Form::label('producto', 'Peso Pallet',
				['class' => 'control-label']) !!}
			{!! Form::text('peso_real', null, ['class' => 'form-control', 'id' => 'peso_real']) !!}
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			{!! Form::label('cajas', 'Cantidad Cajas',
				['class' => 'control-label']) !!}
			{!! Form::number('unidades', 0, ['class' => 'form-control' , 'id' => 'cajas']) !!}
		</div>
	</div>
</div>
