<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calibre extends Model
{
    //
    use SoftDeletes;

    protected $table = 'calibre';
    protected $primaryKey = 'calibre_id';
    protected $fillable = [ 'calibre_nombre',
    						'calibre_unidad_medida_id'];
}
