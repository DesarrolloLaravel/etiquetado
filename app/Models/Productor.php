<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productor extends Model
{
    //
    use SoftDeletes;

    protected $table = 'productor';
    protected $primaryKey = 'productor_id';
    protected $fillable = [ 'productor_name'];
    protected $dates = ['deleted_at'];

    public function lotes()
    {
        return $this->hasMany(  'App\Models\Lote',
                                'lote_productor_id',
                                'productor_id');
    }
}
