<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    //
    use SoftDeletes;

    protected $table = 'cliente';
    protected $primaryKey = 'cliente_id';
    protected $fillable = [ 'cliente_nombre'];
}
