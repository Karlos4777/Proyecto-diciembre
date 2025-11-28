<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Catalogo;

class CatalogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalogos = [
            ['nombre' => 'Novedades 2025', 'descripcion' => 'Los lanzamientos más recientes del año'],
            ['nombre' => 'Clásicos Atemporales', 'descripcion' => 'Los álbumes que marcaron historia'],
            ['nombre' => 'Ofertas Especiales', 'descripcion' => 'Discos con descuentos increíbles'],
            ['nombre' => 'Ediciones Limitadas', 'descripcion' => 'Vinilos y ediciones especiales para coleccionistas'],
            ['nombre' => 'Lo Más Vendido', 'descripcion' => 'Los discos más populares del momento'],
        ];

        foreach ($catalogos as $catalogo) {
            Catalogo::firstOrCreate(
                ['nombre' => $catalogo['nombre']],
                ['descripcion' => $catalogo['descripcion']]
            );
        }
    }
}
