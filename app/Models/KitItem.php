<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['kit_id', 'producto_id', 'cantidad'];

    public function kit()
    {
        return $this->belongsTo(Kit::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
