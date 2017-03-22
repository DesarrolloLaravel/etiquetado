<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variante extends Model
{
    //
	use SoftDeletes;

	protected $table = 'variante';
	protected $primaryKey = 'variante_id';

	protected $fillable = ['variante_name'];
	protected $dates = ['delete_at'];

	function producto(){
		return $this->hasMany('App\Models\Producto','producto_variante_id','variante_id');
	} 



}
