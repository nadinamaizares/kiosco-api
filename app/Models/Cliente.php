<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public $timestamps = false;
    protected $fillable = ['nombre', 'apellido', 'dni', 'celular', 'mail', 'user_id'];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fiados()
    {
        return $this->hasMany(Fiado::class);
    }
}
