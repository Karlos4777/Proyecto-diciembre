<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoReferencia extends Model
{
    protected $table = 'pedido_referencias';

    protected $fillable = [
        'pedido_id', 'user_id', 'filename', 'path', 'mime', 'size'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
