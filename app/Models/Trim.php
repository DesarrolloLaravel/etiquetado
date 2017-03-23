<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trim extends Model
{
    //
    use SoftDeletes;

    protected $table = 'trim';
    protected $primaryKey = 'trim_id';
    protected $fillable = [ 'trim_name'];

    function producto(){
		return $this->hasMany('App\Models\Producto','producto_trim_id','trim_id');
	} 

}
