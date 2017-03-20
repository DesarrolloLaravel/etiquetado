<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    //
    use SoftDeletes;

    protected $table = 'cliente';
    protected $primaryKey = 'cliente_id';
    protected $fillable = [ 'cliente_nombre'];

    public function ordenDespachos(){

    	return $this->hasMany('App\Models\OrdenDespacho','orden_cliente_id','cliente_id'); 
    }
    public function ordenProducciones(){

    	return $this->hasMany('App\Models\OrdenProduccion','orden_cliente_id','cliente_id'); 
    }
}
