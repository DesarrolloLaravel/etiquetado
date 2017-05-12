<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    //
    use SoftDeletes;

    protected $table = 'producto';
    protected $primaryKey = 'producto_id';
    protected $fillable = [ 'producto_nombre',
                            'producto_fullname',
    						'producto_especie_id',
    						'producto_condicion_id',
                            'producto_formato_id',
                            'producto_trim_id',
                            'producto_variante_id',
    						'producto_empaque_id',
                            'producto_calidad_id',
                            'producto_calibre_id',
                            'producto_envase1_id',
                            'producto_envase2_id',
    						'producto_descripcion',
    						'producto_codigo',
                            'producto_v2_id',
    						'producto_peso'];
    protected $dates = ['deleted_at'];
    //agregar propiedad por default
    protected $appends = ['fullName'];
    protected $with = ['especie', 'formato', 'trim', 'calibre', 'calidad'];

    public function orden_produccion()
    {
        return $this->belongsToMany('App\Models\OrdenProduccion',
                                    'op_producto',
                                    'op_producto_producto_id',
                                    'op_producto_orden_id')
            ->withTimestamps();
    }


    public function etiqueta_mp(){
        return $this->hasMany('App\Models\Etiqueta_MP',
                             'etiqueta_mp_producto_id',
                             'producto_id');
    }


    public function cajas()
    {
        return $this->hasManyThrough('App\Models\Caja',
                                    'App\Models\OrdenProduccionProducto',
                                    'op_producto_producto_id',
                                    'caja_op_producto_id');
    }

    public function getFullName($idioma = null)
    {
        $especie = $this->especie ? $this->especie->especie_comercial_name : '';
        $formato = $this->formato ? $this->formato->formato_nombre : '';
        $condicion = $this->condicion ? $this->condicion->condicion_name : '';
        $producto = $this->producto_condicion_id == 0 ? '' : $this->producto_condicion_id;
        $trim = $this->trim ? $this->trim->trim_nombre : '';
        $calibre = $this->calibre ? $this->calibre->calibre_nombre : '';
        $variante = $this->variante ? $this->variante->variante_name : '';
        $productov2 = $this->producto_v2_id == 0 ? '' : $this->producto_v2_id;
        $calidad = $this->calidad ? $this->calidad->calidad_nombre : '';

        return $especie." ".
               $formato." ".
               $condicion." ".
               $trim." ".
               $variante." ".
               $productov2." ".
               $calidad." ".
               $calibre;
    }

    public function getFullNameAttribute()
    {
        $formato = $this->formato ? $this->formato->formato_nombre : '';
        $especie = $this->especie ? $this->especie->especie_abbreviation : '';
        $condicion = $this->condicion ? $this->condicion->condicion_name : '';
        $producto = $this->producto_condicion_id == 0 ? '' : $this->producto_condicion_id;
        $trim = $this->trim ? $this->trim->trim_nombre : '';
        $calibre = $this->calibre ? $this->calibre->calibre_nombre : '';
        $variante = $this->variante ? $this->variante->variante_name : '';
        $productov2 = $this->producto_v2_id == 0 ? '' : $this->producto_v2_id;
        $calidad = $this->calidad ? $this->calidad->calidad_nombre : '';

        return $especie." ".
            $condicion." ".
            $formato." ".
            $trim." ".
            $calibre." ".
            $variante." ".
            $productov2." ".
            $calidad;
    }

    public function calibre(){
        return $this->belongsTo('App\Models\Calibre',
            'producto_calibre_id',
            'calibre_id');
    }

    public function especie(){
        return $this->belongsTo('App\Models\Especie',
            'producto_especie_id',
            'especie_id');
    }

    public function formato(){
        return $this->belongsTo('App\Models\Formato',
            'producto_formato_id',
            'formato_id');
    }

    public function trim(){
        return $this->belongsTo('App\Models\Trim',
            'producto_trim_id',
            'trim_id');
    }

    public function calidad(){
        return $this->belongsTo('App\Models\Calidad',
            'producto_calidad_id',
            'calidad_id');
    }

    public function envase(){
        return $this->belongsTo('App\Models\Envase',
            'producto_envase1_id',
            'envase_id');
    }

    public function envaseDos(){
        return $this->belongsTo('App\Models\Envase_Dos',
            'producto_envase2_id',
            'envaseDos_id');
    }

    public function variante(){
        return $this->belongsTo('App\Models\Variante',
            'producto_variante_id',
            'variante_id');
    }

    public function varianteDos(){
        return $this->belongsTo('App\Models\VarianteDos',
            'producto_v2_id',
            'varianteDos_id');
    }

    public function condicion(){
        return $this->belongsTo('App\Models\Condicion',
            'producto_condicion_id',
            'condicion_id');
    }
}
