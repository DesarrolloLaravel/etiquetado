<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VarianteDos extends Model
{
    //
    use SoftDeletes;

	protected $table = 'variantedos';
	protected $primaryKey = 'varianteDos_id';

	protected $fillable = ['varianteDos_name'];
	protected $dates = ['delete_at'];

	function producto(){
		return $this->hasMany('App\Models\Producto','producto_v2_id','varianteDos_id');
	} 
}
