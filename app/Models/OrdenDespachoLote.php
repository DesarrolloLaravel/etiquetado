<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenDespachoLote extends Model
{
    //
    use SoftDeletes;

    protected $table = 'despacho_lote';
    protected $primaryKey = 'despacho_id';
    protected $fillable = [ 'despacho_orden_id',
    						'despacho_lote_id',
    						'despacho_producto_id',
    						'despacho_cajas_plan',
    						'despacho_kilos_plan'];

    protected $dates = ['deleted_at'];

    public function lote()
    {
        return $this->belongsTo('App\Models\Lote',
                                'despacho_lote_id',
                                'lote_id');
    }

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto',
                                'despacho_producto_id',
                                'producto_id');
    }

    public function cajas()
    {
        return $this->belongsToMany('App\Models\Caja',
                                    'despacho_caja',
                                    'despacho_caja_despacho_lote_id',
                                    'despacho_caja_caja_id')
            ->withTimestamps();
    }

    public function all_cajas()
    {
        return $this->belongsToMany('App\Models\Caja',
                                    'despacho_caja',
                                    'despacho_caja_despacho_lote_id',
                                    'despacho_caja_caja_id')
                    ->withTrashed();
    }

    public function despacho_caja()
    {
        return $this->hasMany(  'App\Models\OrdenDespachoCaja',
                                'despacho_caja_despacho_lote_id',
                                'despacho_caja_id');
    }

    public function orden()
    {
        return $this->belongsTo('App\Models\OrdenDespacho',
            'despacho_orden_id',
            'orden_id');
    }
}