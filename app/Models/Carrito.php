<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrito extends Model
{
    protected $fillable = ['user_id', 'contenido'];

    protected $casts = [
        'contenido' => 'array', // Laravel convierte JSON <-> array automáticamente
    ];
}
