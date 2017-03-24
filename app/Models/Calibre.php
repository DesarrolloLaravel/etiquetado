<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Collection;
class Calibre extends Model
{
    //
    use SoftDeletes;

    protected $table = 'calibre';
    protected $primaryKey = 'calibre_id';
    protected $fillable = [ 'calibre_nombre',
    						'calibre_unidad_medida_id'];

 	public function unidad_medida(){
 		return $this->belongsto('App\Models\UnidadMedida',
 		 	'calibre_unidad_medida_id',
 		 	'unidad_medida_id');
 	}
}
