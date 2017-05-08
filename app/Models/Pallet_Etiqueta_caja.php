<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pallet_Etiqueta_caja extends Model
{
    use SoftDeletes;

    protected $table = 'pallet_etiqueta_caja';
    protected $primaryKey = 'pec_id';
    protected $fillable = [ 'pec_pallet_id',
                            'pec_etiqueta_id'
                            ];
    protected $dates = ['deleted_at'];

    public function pallet_pt(){
    	return $this->belongsTo('App\Models\Pallet_PT',
    							'pec_pallet_id',
    							'pallet_pt_id');
    }

    public function etiqueta(){
    	return $this->belongsTo('App\Models\Etiqueta',
    							'pec_etiqueta_id',
    							'etiqueta_id');

    }
}
