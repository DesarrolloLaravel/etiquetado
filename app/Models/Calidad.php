<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calidad extends Model
{
    //
    use SoftDeletes;

    protected $table = 'calidad';
    protected $primaryKey = 'calidad_id';
    protected $fillable = [ 'calidad_nombre'];
}
