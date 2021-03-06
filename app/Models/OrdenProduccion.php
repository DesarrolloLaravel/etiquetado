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
    protected $fillable = [ 'orden_descripcion',
                            'orden_fecha',
                            'orden_fecha_inicio',
                            'orden_fecha_compromiso',
                            'orden_cliente_id'];

    protected $dates = ['deleted_at'];

    
    public function ordenProductos()
    {
        return $this->hasMany('App\Models\OrdenProduccionProducto',
                                    'op_producto',
                                    'op_producto_orden_id',
                                    'op_producto_producto_id',
                                    'op_producto_kilos_declarados');
    }

    public function ordenTrabajo()
    {
        return $this->hasMany('App\Models\OrdenTrabajo','orden_id',
                                    'orden_trabajo_orden_produccion');
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