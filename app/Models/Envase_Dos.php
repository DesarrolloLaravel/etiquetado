<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Envase_Dos extends Model
{
    //
    use SoftDeletes;

    protected $table = "envasedos";

    protected $primaryKey = 'envaseDos_id';
    protected $fillable = [ 'envaseDos_nombre',
        'envaseDos_capacidad'];

    public function productos(){

    	return $this->hasMany('App\Models\Producto','producto_envase2_id','envaseDos_id');
    }
}
