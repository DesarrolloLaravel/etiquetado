<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CajaPosicion extends Model
{
    //
    use SoftDeletes;

    protected $table = 'caja_posicion';
    protected $primaryKey = 'caja_posicion_id';
    protected $fillable = [ 'caja_posicion_caja_id',
    						'caja_posicion_posicion_id'];

    protected $dates = ['deleted_at'];
}
