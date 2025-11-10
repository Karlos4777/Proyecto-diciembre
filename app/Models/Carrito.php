<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'contenido'];

    protected $casts = [
        'contenido' => 'array', // convierte automáticamente JSON a array
    ];
}
