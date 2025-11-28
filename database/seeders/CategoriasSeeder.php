<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Rock', 'descripcion' => 'Música Rock de todos los tiempos'],
            ['nombre' => 'Pop', 'descripcion' => 'Los mejores éxitos del Pop'],
            ['nombre' => 'Jazz', 'descripcion' => 'Clásicos del Jazz'],
            ['nombre' => 'Electrónica', 'descripcion' => 'Música Electrónica y Dance'],
            ['nombre' => 'Clásica', 'descripcion' => 'Música Clásica y Orquestal'],
            ['nombre' => 'Hip Hop', 'descripcion' => 'Hip Hop y Rap'],
            ['nombre' => 'Reggae', 'descripcion' => 'Reggae y Ska'],
            ['nombre' => 'Blues', 'descripcion' => 'Blues tradicional y moderno'],
            ['nombre' => 'Country', 'descripcion' => 'Country y Folk'],
            ['nombre' => 'Metal', 'descripcion' => 'Heavy Metal y subgéneros'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['nombre' => $categoria['nombre']],
                ['descripcion' => $categoria['descripcion']]
            );
        }
    }
}
