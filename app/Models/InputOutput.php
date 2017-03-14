<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InputOutput extends Model
{
    //
    use SoftDeletes;

    protected $table = 'input_output';
    protected $primaryKey = 'io_id';
    protected $fillable = [ 'io_posicion_id',
    						'io_caja_id',
    						'io_tipo',
    						'io_proceso'];
    protected $dates = ['deleted_at'];
}
