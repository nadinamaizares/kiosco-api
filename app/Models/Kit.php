<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kit extends Model
{
    public $timestamps = false;
    protected $fillable = ['nombre', 'descripcion'];

    public function kitItems()
    {
        return $this->hasMany(KitItem::class);
    }
}
