<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiadoItem extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'fiado_id', 'producto_nombre', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal',
    ];
    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function fiado()
    {
        return $this->belongsTo(Fiado::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
