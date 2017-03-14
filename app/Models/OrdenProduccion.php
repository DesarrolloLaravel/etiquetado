<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenProduccion extends Model
{
    //
    use SoftDeletes;

    protected $table = 'orden_produccion';
    protected $primaryKey = 'orden_id';
    protected $fillable = [ 'orden_lote_id',
    						'orden_descripcion',
    						'orden_fecha',
    						'orden_fecha_inicio',
    						'orden_fecha_compromiso',
    						'orden_cliente_id',
    						'orden_ciudad_id',
    						'orden_provincia_id'];

    protected $dates = ['deleted_at'];

    public function lote()
    {
        return $this->belongsTo('App\Models\Lote',
                                'orden_lote_id',
                                'lote_id');
    }

    public function productos()
    {
        return $this->belongsToMany('App\Models\Producto',
        							'op_producto',
        							'op_producto_orden_id',
        							'op_producto_producto_id')
                ->withPivot('op_producto_id')
                ->withTimestamps();
    }

    public function cajas()
    {
        return $this->hasManyThrough('App\Models\Caja',
                                    'App\Models\OrdenProduccionProducto',
                                    'op_producto_orden_id',
                                    'caja_op_producto_id');
    }

    public function historyCajas()
    {
        return $this->hasManyThrough('App\Models\Caja',
            'App\Models\OrdenProduccionProducto',
            'op_producto_orden_id',
            'caja_op_producto_id')
            ->withTrashed();
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente',
            'orden_cliente_id',
            'cliente_id');
    }
}
