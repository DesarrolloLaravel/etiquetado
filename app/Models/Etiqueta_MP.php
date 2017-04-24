<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

class Etiqueta_MP extends Model
{
    //
    use SoftDeletes;

    protected $table = 'etiqueta_mp';
    protected $primaryKey = 'etiqueta_mp_id';
    protected $fillable = [ 'etiqueta_mp_lote_id',
                            'etiqueta_mp_estado',
    						'etiqueta_mp_producto_id',
    						'etiqueta_mp_barcode',
    						'etiqueta_mp_fecha',
    						'etiqueta_mp_peso',
    						'etiqueta_mp_cantidad_cajas',
                            'etiqueta_mp_posicion'];
    protected $dates = ['deleted_at'];

    public function lote(){

        return $this->belongsTo('App\Models\Lote',
                             'etiqueta_mp_lote_id',
                             'lote_id');
    }

    public function producto(){
        return $this->belongsTo('App\Models\Producto',
                             'etiqueta_mp_producto_id',
                             'producto_id');
    }

    public function camara(){
        return $this->belongsTo('App\Models\Camara',
                             'etiqueta_mp_posicion',
                             'camara_id');
    }

}
