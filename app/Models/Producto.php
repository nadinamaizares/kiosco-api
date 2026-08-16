<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'codigo_barras', 'nombre', 'marca', 'categoria', 'especificacion',
        'precio_costo', 'precio_venta', 'stock_actual', 'stock_minimo', 'proveedor',
    ];
    protected $casts = [
        'precio_costo' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function kitItems()
    {
        return $this->hasMany(KitItem::class);
    }

    public function fiadoItems()
    {
        return $this->hasMany(FiadoItem::class);
    }
}
