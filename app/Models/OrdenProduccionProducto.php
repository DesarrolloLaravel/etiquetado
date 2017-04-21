<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenProduccionProducto extends Model
{
    //
    use SoftDeletes;

    protected $table = 'op_producto';
    protected $primaryKey = 'op_producto_id';
    protected $fillable = [ 'op_producto_orden_id',
                            'op_producto_producto_id',
                            'op_producto_kilos_declarados'];

    protected $dates = ['deleted_at'];

    public function producto()
    {
        return $this->belongsTo('etiquetado\Models\Producto',
                                'op_producto_producto_id',
                                'producto_id');
    }

    public function orden()
    {
        return $this->belongsTo('etiquetado\Models\OrdenProduccion',
                                'op_producto_orden_id',
                                'orden_id');
    }
}

