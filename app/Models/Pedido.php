<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['user_id','contenido', 'estado', 'fecha', 'total'];
    protected $casts = [
        'contenido' => 'array', // para que se convierta automáticamente de/para JSON
    ];
    // Línea de pedido (detalle de cada producto) - renamed to avoid collision
    public function lineas()
    {
        return $this->hasMany(PedidoDetalle::class, 'pedido_id');
    }
    // Alias usado por las vistas antiguas: detalles == lineas
    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class, 'pedido_id');
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