<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procesador extends Model
{
    //
    use SoftDeletes;

    protected $table = 'procesador';
    protected $primaryKey = 'procesador_id';
    protected $fillable = [ 'procesador_name',
                            'procesador_rut'];
    protected $dates = ['deleted_at'];

    public function lotes()
    {
        return $this->hasMany(  'App\Models\Lote',
                                'lote_procesador_id',
                                'procesador_id');
    }

    public function disable()
    {
        $this->procesador_enable = 0;
    }
}
