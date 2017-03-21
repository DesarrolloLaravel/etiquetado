<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadMedida extends Model
{
    //
    use SoftDeletes;

    protected $table='unidad_medida';
    protected $primaryKey = 'unidad_medida_id';
    protected $fillable = [ 'unidad_medida_nombre',
    						'unidad_medida_abreviacion'];
}
