<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'tipo', 'producto_id', 'cantidad', 'precio_unitario', 'usuario_id', 'notas',
    ];
    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
