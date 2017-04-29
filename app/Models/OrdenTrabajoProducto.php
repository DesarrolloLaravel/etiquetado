<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajoProducto extends Model
{
    use SoftDeletes;

    protected $table = 'ot_producto';
    protected $primaryKey = 'ot_producto_id';

    protected $fillable = ['ot_producto_orden_trabajo',
    						'ot_producto_etiqueta_pallet'];

	protected $dates = ['deleted_at'];

	public function etiqueta(){
		return $this->belongsTo('App\Models\Etiqueta_MP',
                                'ot_producto_etiqueta_pallet',
                               	'etiqueta_mp_id');
	}
}
