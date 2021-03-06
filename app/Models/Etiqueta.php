<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etiqueta extends Model
{
    //
    use SoftDeletes;

    protected $table = 'etiqueta';
    protected $primaryKey = 'etiqueta_id';
    protected $fillable = [ 'etiqueta_caja_id',
    						'etiqueta_barcode',
                            'etiqueta_lote_id',
    						'etiqueta_fecha',
                            'etiqueta_estado'];

    protected $dates = ['deleted_at'];

    public function lote()
    {
        return $this->belongsTo('App\Models\Lote',
                                'etiqueta_lote_id',
                                'lote_id');
    }

    public function caja()
    {
        return $this->belongsTo('App\Models\Caja',
                                'etiqueta_caja_id',
                                'caja_id');
    }

    public function etiqueta(){
        return $this->belongsTo('App\Models\Etiqueta',
                                'etiqueta_lote_id',
                                'lote_id');
    }
    public function pallet_etiqueta(){
        return $this->hasMany('App\Models\Pallet_Etiqueta_caja',
                                'pec_etiqueta_id',
                                'etiqueta_id');

    }


}
