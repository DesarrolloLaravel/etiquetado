<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destino extends Model
{
    //
    use SoftDeletes;

    protected $table = 'destino';
    protected $primaryKey = 'destino_id';
    protected $fillable = [ 'destino_name'];

    protected $dates = ['deleted_at'];

    public function lotes()
    {
        return $this->hasMany(  'App\Models\Lote',
                                'lote_destino_id',
                                'destino_id');
    }
}
