<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Collection;

class Lote extends Model
{
    //
    use SoftDeletes;

    //tipo_id corresponde a formato mp
    //variedad sera reemplazado por destino preliminar

    protected $primaryKey = 'lote_id';
    protected $table = 'lote';
    protected $fillable = [ 'lote_users_id',
                            'lote_procesador_id',
                            'lote_elaborador_id',
                            'lote_productor_id',
                            'lote_especie_id',
                            'lote_destino_id',
                            'lote_calidad_id',
                            'lote_mp_id',
                            'lote_fecha_documento',
                            'lote_fecha_planta',
                            'lote_fecha_expiracion',
                            'lote_n_documento',
                            'lote_kilos_declarado',
                            'lote_kilos_recepcion',
                            'lote_cajas_declarado',
                            'lote_cajas_recepcion',
                            'lote_cliente_id',
                            'lote_observaciones',
                            'lote_djurada',
                            'lote_reestriccion',
                            'lote_year',
                            'lote_produccion',
                            'lote_tipo_id',
                            'lote_condicion'];
    protected $dates = ['deleted_at'];

    public function go_produccion()
    {
        $this->lote_produccion = 'SI';
    }

    public function quit_produccion()
    {
        $this->lote_produccion = 'NO';
    }

    public function calidad(){
        return $this->belongsTo('App\Models\Calidad',
            'producto_calidad_id',
            'calidad_id');
    }

    public function etiqueta_mp(){
        return $this->hasMany('App\Models\Etiqueta_MP',
                             'etiqueta_mp_lote_id',
                             'lote_id');
    }

    public function etiqueta(){
        return $this->hasMany('App\Models\Etiqueta',
                             'etiqueta_lote_id',
                             'lote_id');
    }



    public function especie()
    {
        return $this->belongsTo('App\Models\Especie',
                                'lote_especie_id',
                                'especie_id');
    }

    public function orden_produccion()
    {
        return $this->hasMany(  'App\Models\OrdenProduccion',
                                'orden_lote_id',
                                'lote_id');
    }

    public function cajas()
    {
        return $this->hasManyThrough('App\Models\Caja',
                                    'App\Models\OrdenProduccion',
                                    'orden_lote_id',
                                    'caja_orden_p_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User',
                                'lote_users_id',
                                'users_id');
    }

    public function procesador()
    {
        return $this->belongsTo('App\Models\Procesador',
                                'lote_procesador_id',
                                'procesador_id');
    }

    public function materia_prima()
    {
        return $this->belongsTo('App\Models\MateriaPrima',
                                'lote_mp_id',
                                'materia_prima_id');
    }

    public function productor()
    {
        return $this->belongsTo('App\Models\Productor',
                                'lote_productor_id',
                                'productor_id');
    }

    public function elaborador()
    {
        return $this->belongsTo('App\Models\Elaborador',
                                'lote_elaborador_id',
                                'elaborador_id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente',
                                'lote_cliente_id',
                                'cliente_id');
    }

    public function getLastCajaNumber()
    {
        $cajas = $this->cajas()->withTrashed()->get();

        if($cajas->count() == 0)
        {
            return 0;
        }
        else
        {
            return $cajas->max('caja_number');
        }
    }

    public function getProducts()
    {
        /*
        funcion para retornar los productos que se gan generado sobre un lote,
        validando sobre las ordenes de produccion, y verificando que existan al menos
        una caja recepcionada sobre esa orden
        */

        //FALTA OPTIMIZAR EL TEMA DEL DESPACHO AL MOMENTO DE VER LA ORDEN
        $ordenes = $this->orden_produccion;

        $productos = new Collection;
        foreach ($ordenes as $orden) {
            # code...
            if($orden->cajas()->count() > 0)
            {
                $productos = $productos->merge($orden->productos()->has('cajas')->get());
            }
        }
        
        return $productos;
    }

    public function getCajas()
    {
        /*
        funcion que retorna todas las cajas de un lote, validando que
        tengan esten en stock, y que pertenezcan a alguna de las ordenes
        del lote
        */
        $cajas = Caja::has('etiqueta')
                ->whereHas('etiqueta.lote', function($q){
                    $q->where('lote_id', '=',$this->lote_id);
                })->get();

        return $cajas;
    }

    public function getCajasByProduct($producto_id)
    {
        $cajas = Caja::has('caja_posicion')
            ->whereHas('orden_producto.orden.lote', function($q){
                $q->where('lote_id', '=', $this->lote_id);
            })
            ->whereHas('orden_producto.producto', function($q) use ($producto_id){
                $q->where('producto_id', '=', $producto_id);
            })
            ->get();

        return $cajas;
    }

}
