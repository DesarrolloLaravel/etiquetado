<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MateriaPrima extends Model
{
    //
    use SoftDeletes;

    protected $table = 'materia_prima';
    protected $primaryKey = 'materia_prima_id';
    protected $fillable = [ 'materia_prima_name'];

    protected $dates = ['deleted_at'];

    public function lotes()
    {
        return $this->hasMany(  'App\Models\Lote',
                                'lote_mp_id',
                                'materia_prima_id');
    }
}
