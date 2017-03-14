<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Elaborador extends Model
{
    //
    use SoftDeletes;

    protected $table = 'elaborador';
    protected $primaryKey = 'elaborador_id';
    protected $fillable = [ 'elaborador_name',
                            'elaborador_rut'];
    protected $dates = ['deleted_at'];

    public function lotes()
    {
        return $this->hasMany(  'App\Models\Lote',
                                'lote_empresa_id',
                                'elaborador_id');
    }

    public function disable()
    {
        $this->empresa_enable = 0;
    }
}
