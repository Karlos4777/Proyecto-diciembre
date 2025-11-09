<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $table = 'catalogos';
    protected $fillable = ['nombre', 'descripcion'];
    public function productos()
{
    return $this->hasMany(Producto::class);
}
}
