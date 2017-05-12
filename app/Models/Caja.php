<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    //
    use SoftDeletes;

    protected $table = 'caja';
    protected $primaryKey = 'caja_id';
    protected $fillable = [ 'caja_ot_producto_id',
                            'caja_peso_real',
                            'caja_peso_bruto',
                            'caja_glaseado',
                            'caja_unidades',
                            'caja_estado'];

    protected $dates = ['deleted_at'];

    public function orden()
    {
        return $this->belongsTo('App\Models\OrdenProduccion',
                                'caja_orden_p_id',
                                'orden_id');
    }

    public function orden_producto()
    {
        return $this->belongsTo('App\Models\OrdenTrabajo',
                                'caja_ot_producto_id',
                                'orden_trabajo_id');
    }

    public function etiqueta()
    {
        return $this->hasOne('App\Models\Etiqueta',
                                'etiqueta_caja_id',
                                'caja_id');
    }

    

    public function posicion_caja()
    {
        return $this->hasMany(  'App\Models\CajaPosicion',
                                'caja_posicion_caja_id',
                                'caja_id');
    }

    public function caja_posicion()
    {
        return $this->belongsToMany('App\Models\Posicion',
                                    'caja_posicion',
                                    'caja_posicion_caja_id',
                                    'caja_posicion_posicion_id')
                    ->withTimestamps();
    }

    public function input_output()
    {
        return $this->belongsToMany('App\Models\Posicion',
                                    'input_output',
                                    'io_caja_id',
                                    'io_posicion_id')
                    ->withPivot('io_id','io_tipo','io_proceso')
                    ->withTimestamps();
    }

    public function despachoCaja()
    {
        return $this->hasOne('App\Models\OrdenDespachoCaja',
            'despacho_caja_caja_id',
            'caja_id'
            );
    }

}
