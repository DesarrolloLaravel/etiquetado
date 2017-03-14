<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenDespacho extends Model
{
    //
    use SoftDeletes;

    protected $table = 'orden_despacho';
    protected $primaryKey = 'orden_id';
    protected $fillable = [ 'orden_estado',
    						'orden_cliente_id',
                            'orden_guia',
    						'orden_fecha'];

    protected $dates = ['deleted_at'];

    public function productos()
    {
        return $this->belongsToMany('App\Models\Producto',
        							'despacho_lote',
        							'despacho_orden_id',
        							'despacho_producto_id')
            ->withTimestamps();
    }

    public function lotes()
    {
        return $this->belongsToMany('App\Models\Lote',
                                    'despacho_lote',
                                    'despacho_orden_id',
                                    'despacho_lote_id')
            ->withTimestamps();
    }

    public function despacho_lote()
    {
    	return $this->hasMany(  'App\Models\OrdenDespachoLote',
                                'despacho_orden_id',
                                'orden_id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente',
            'orden_cliente_id',
            'cliente_id');
    }
}
