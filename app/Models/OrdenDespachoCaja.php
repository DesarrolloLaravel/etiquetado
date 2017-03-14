<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenDespachoCaja extends Model
{
    //
    use SoftDeletes;

    protected $table = 'despacho_caja';
    protected $primaryKey = 'despacho_caja_id';
    protected $fillable = [ 'despacho_caja_caja_id',
    						'despacho_caja_despacho_lote_id'];

    protected $dates = ['deleted_at'];

    public function despachoLote()
    {
        return $this->belongsTo('App\Models\OrdenDespachoLote',
            'despacho_caja_despacho_lote_id',
            'despacho_id');
    }

    
}
