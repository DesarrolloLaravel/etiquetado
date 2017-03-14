<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Camara extends Model
{
    //
    use SoftDeletes;

    protected $table = 'camara';
    protected $primaryKey = 'camara_id';
    protected $fillable = [ 'camara_nombre',
    						'camara_frigorifico_id'];
    protected $dates = ['deleted_at'];

    public function posiciones()
    {
        return $this->hasMany(  'App\Models\Posicion',
                                'posicion_camara_id',
                                'camara_id');
    }

    public function frigorifico()
    {
        return $this->belongsTo('App\Models\Frigorifico',
                                'camara_frigorifico_id',
                                'frigorifico_id');
    }

    public function cajas()
    {
        return $this->hasManyThrough('App\Models\CajaPosicion',
                                    'App\Models\Posicion',
                                    'posicion_camara_id',
                                    'caja_posicion_posicion_id');
    }

}
