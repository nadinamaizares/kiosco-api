<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fiado extends Model
{
    public $timestamps = false;
    protected $fillable = ['cliente_id', 'total', 'pagado', 'estado', 'notas', 'user_id'];
    protected $casts = [
        'total' => 'decimal:2',
        'pagado' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    const ESTADOS = ['pendiente', 'pagado_parcial', 'pagado'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fiadoItems()
    {
        return $this->hasMany(FiadoItem::class);
    }
}
