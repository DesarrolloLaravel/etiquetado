<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Envase extends Model
{
    //
    use SoftDeletes;

    protected $table = 'envase';
    protected $primaryKey = 'envase_id';
    protected $fillable = [ 'envase_nombre',
        'envase_capacidad'];
}
