<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Frigorifico extends Model
{
    //
    use SoftDeletes;

    protected $table = 'frigorifico';
    protected $primaryKey = 'frigorifico_id';
    protected $fillable = [ 'frigorifico_nombre'];
    protected $dates = ['deleted_at'];

    public function camaras()
    {
        return $this->hasMany(  'App\Models\Camara',
                                'camara_frigorifico_id',
                                'frigorifico_id');
    }

    public function posiciones()
    {
        return $this->hasManyThrough('App\Models\Posicion',
                                    'App\Models\Camara',
                                    'camara_frigorifico_id',
                                    'posicion_camara_id');
    }

}
