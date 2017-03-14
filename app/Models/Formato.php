<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formato extends Model
{
    //
    use SoftDeletes;

    protected $table = 'formato';
    protected $primaryKey = 'formato_id';
    protected $fillable = [ 'formato_nombre',
                            'formato_abreviatura'];
}
