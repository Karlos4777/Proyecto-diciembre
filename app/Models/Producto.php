<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'precio',
        'cantidad',
        'descripcion',
        'imagen',
        'categoria_id',
        'catalogo_id', // <- agregado
    ];

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación con catálogo
    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class);
    }

    public function getImagenUrlAttribute()
{
    if ($this->imagen && file_exists(storage_path('app/public/' . $this->imagen))) {
        return asset('storage/' . $this->imagen);
    }

    return asset('img/no-image.png'); // Imagen por defecto
}

}
