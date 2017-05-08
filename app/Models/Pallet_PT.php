<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pallet_PT extends Model
{
    use SoftDeletes;

    protected $table = 'pallet_pt';
    protected $primaryKey = 'pallet_pt_id';
    protected $fillable = [ 'pallet_pt_barcode'];
    protected $dates = ['deleted_at'];

    public function pallet_etiqueta(){
    	return $this->hasMany('App\Models\Pallet_Etiqueta_caja',
    						  'pec_pallet_id',
    						  'pallet_pt_id');
    }

}
