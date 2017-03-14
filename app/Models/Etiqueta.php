<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etiqueta extends Model
{
    //
    use SoftDeletes;

    protected $table = 'etiqueta';
    protected $primaryKey = 'etiqueta_id';
    protected $fillable = [ 'etiqueta_caja_id',
    						'etiqueta_barcode',
    						'etiqueta_fecha',
                            'etiqueta_estado'];

    protected $dates = ['deleted_at'];

    public function caja()
    {
        return $this->belongsTo('App\Models\Caja',
                                'etiqueta_caja_id',
                                'caja_id');
    }

}
