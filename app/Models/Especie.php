<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especie extends Model
{
    //
    use SoftDeletes;

    protected $table = 'especie';
    protected $primaryKey = 'especie_id';
    protected $fillable = [ 'especie_name',
        'especie_comercial_name',
        'especie_abbreviation'];

    public function lotes()
    {
        return $this->hasMany(  'App\Models\Lote',
                                'lote_especie_id',
                                'especie_id');
    }
}
