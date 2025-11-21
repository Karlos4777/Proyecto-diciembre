<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['user_id','contenido', 'estado', 'fecha', 'total','detalles',
    ];
    protected $casts = [
        'contenido' => 'array', // para que se convierta automáticamente de/para JSON
    ];
    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function referencias()
    {
        return $this->hasMany(\App\Models\PedidoReferencia::class);
    }
}
