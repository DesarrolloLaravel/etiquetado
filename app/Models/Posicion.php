<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posicion extends Model
{
    //
    use SoftDeletes;

    protected $table = 'posicion';
    protected $primaryKey = 'posicion_id';
    protected $fillable = [ 'posicion_nombre',
    						'posicion_cajas',
    						'posicion_camara_id'];
    protected $dates = ['deleted_at'];

    public function camara()
    {
        return $this->belongsTo('App\Models\Camara',
                                'posicion_camara_id',
                                'camara_id');
    }

    public function caja_posicion()
    {
        return $this->belongsToMany('App\Models\Caja',
                                    'caja_posicion',
                                    'caja_posicion_posicion_id',
                                    'caja_posicion_caja_id')
                    ->withTimestamps();
    }

    public function input_output()
    {
        return $this->belongsToMany('App\Models\Caja',
                                    'input_output',
                                    'io_posicion_id',
                                    'io_caja_id')
                    ->withPivot('io_id','io_tipo','io_proceso')
                    ->withTimestamps();
    }

}
