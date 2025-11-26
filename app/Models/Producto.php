<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'barcode',
        'nombre',
        'precio',
        'cantidad',
        'descuento',
        'descripcion',
        'imagen',
        'categoria_id',
        'catalogo_id', // <- agregado
        'artista',
        'album',
        'preview_url',
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

    /**
     * Precio con descuento aplicado (precio * (1 - descuento/100)).
     * Si no hay descuento devuelve el precio original.
     */
    public function getPrecioConDescuentoAttribute()
    {
        $descuento = (int) ($this->descuento ?? 0);
        $precio = (float) $this->precio;

        if ($descuento <= 0) {
            return $precio;
        }

        return round($precio * (1 - $descuento / 100), 2);
    }

    public function getTieneDescuentoAttribute()
    {
        return ((int) ($this->descuento ?? 0)) > 0;
    }

    // Reseñas
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getRatingPromedioAttribute()
    {
        $avg = $this->reviews()->where('aprobado', true)->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    public function getRatingCantidadAttribute()
    {
        return $this->reviews()->where('aprobado', true)->count();
    }

}
