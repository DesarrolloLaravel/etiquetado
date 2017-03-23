<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condicion extends Model
{
    //
    use SoftDeletes;

	protected $table = 'condicion';
	protected $primaryKey = 'condicion_id';

	protected $fillable = ['condicion_name'];
	protected $dates = ['delete_at'];

	function producto(){
		return $this->hasMany('App\Models\Producto','condicion_variante_id','condicion_id');
	} 
}
