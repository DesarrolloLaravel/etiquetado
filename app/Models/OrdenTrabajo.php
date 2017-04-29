<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajo extends Model
{
    //
    use SoftDeletes;

    protected $table = 'orden_trabajo';
    protected $primaryKey = 'orden_trabajo_id';

    protected $fillable = ['orden_trabajo_orden_produccion',
                            'orden_trabajo_especie',
                            'orden_trabajo_producto',
    						'orden_trabajo_fecha',
    						'orden_trabajo_peso_total'];

	protected $dates = ['deleted_at'];

	public function ordenTrabajoProducto(){

		return	$this->hasMany('App\Models\OrdenTrabajoProducto',
			'ot_producto_orden_trabajo','orden_trabajo_id');
	}

    public function ordenProduccion(){
        return $this->belongsTo('App\Models\OrdenProduccion',
                                'orden_trabajo_orden_produccion',
                                'orden_id');
    }

    public function especie(){
        return $this->belongsTo('App\Models\Especie',
                                'orden_trabajo_especie',
                                'especie_id');
    }

    public function producto(){
        return $this->belongsTo('App\Models\Producto',
                                'orden_trabajo_producto',
                                'producto_id');
    }

    public function lote(){

        return $this->hasManyThrough('App\Models\Lote','App\Models\Especie','especie_id','lote_especie_id');        
    }

}
