<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Catalogo;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rock = Categoria::where('nombre', 'Rock')->first();
        $pop = Categoria::where('nombre', 'Pop')->first();
        $jazz = Categoria::where('nombre', 'Jazz')->first();
        $electronica = Categoria::where('nombre', 'Electrónica')->first();
        $metal = Categoria::where('nombre', 'Metal')->first();

        $novedades = Catalogo::where('nombre', 'Novedades 2025')->first();
        $clasicos = Catalogo::where('nombre', 'Clásicos Atemporales')->first();
        $ofertas = Catalogo::where('nombre', 'Ofertas Especiales')->first();

        $productos = [
            [
                'codigo' => 'ROCK001',
                'barcode' => '1234567890001',
                'nombre' => 'The Dark Side of the Moon - Pink Floyd',
                'precio' => 345900,
                'cantidad' => 50,
                'descuento' => 0,
                'descripcion' => 'Álbum icónico de rock progresivo lanzado en 1973',
                'imagen' => 'aq-01.jpg',
                'categoria_id' => $rock?->id,
                'catalogo_id' => $clasicos?->id,
            ],
            [
                'codigo' => 'ROCK002',
                'barcode' => '1234567890002',
                'nombre' => 'Led Zeppelin IV',
                'precio' => 325000,
                'cantidad' => 35,
                'descuento' => 10,
                'descripcion' => 'Cuarto álbum de estudio de Led Zeppelin con Stairway to Heaven',
                'imagen' => 'dk-02.jpg',
                'categoria_id' => $rock?->id,
                'catalogo_id' => $clasicos?->id,
            ],
            [
                'codigo' => 'POP001',
                'barcode' => '1234567890003',
                'nombre' => 'Thriller - Michael Jackson',
                'precio' => 299900,
                'cantidad' => 60,
                'descuento' => 15,
                'descripcion' => 'El álbum más vendido de todos los tiempos',
                'imagen' => 'qa-03.jpg',
                'categoria_id' => $pop?->id,
                'catalogo_id' => $clasicos?->id,
            ],
            [
                'codigo' => 'POP002',
                'barcode' => '1234567890004',
                'nombre' => 'Back to Black - Amy Winehouse',
                'precio' => 250000,
                'cantidad' => 40,
                'descuento' => 0,
                'descripcion' => 'Segundo y último álbum de estudio de Amy Winehouse',
                'imagen' => 'uh-04.jpg',
                'categoria_id' => $pop?->id,
                'catalogo_id' => $ofertas?->id,
            ],
            [
                'codigo' => 'JAZZ001',
                'barcode' => '1234567890005',
                'nombre' => 'Kind of Blue - Miles Davis',
                'precio' => 389000,
                'cantidad' => 25,
                'descuento' => 0,
                'descripcion' => 'Obra maestra del jazz modal de 1959',
                'imagen' => 'h8-image.jpg',
                'categoria_id' => $jazz?->id,
                'catalogo_id' => $clasicos?->id,
            ],
            [
                'codigo' => 'JAZZ002',
                'barcode' => '1234567890006',
                'nombre' => 'A Love Supreme - John Coltrane',
                'precio' => 340000,
                'cantidad' => 30,
                'descuento' => 5,
                'descripcion' => 'Álbum conceptual de jazz espiritual de 1965',
                'imagen' => 'w2-image.jpg',
                'categoria_id' => $jazz?->id,
                'catalogo_id' => $clasicos?->id,
            ],
            [
                'codigo' => 'ELEC001',
                'barcode' => '1234567890007',
                'nombre' => 'Discovery - Daft Punk',
                'precio' => 420000,
                'cantidad' => 45,
                'descuento' => 20,
                'descripcion' => 'Segundo álbum de estudio del dúo francés de electrónica',
                'imagen' => 'em-image (1).png',
                'categoria_id' => $electronica?->id,
                'catalogo_id' => $ofertas?->id,
            ],
            [
                'codigo' => 'ELEC002',
                'barcode' => '1234567890008',
                'nombre' => 'Random Access Memories - Daft Punk',
                'precio' => 459900,
                'cantidad' => 38,
                'descuento' => 0,
                'descripcion' => 'Cuarto álbum de estudio ganador de múltiples Grammy',
                'imagen' => 'dx-image (2).png',
                'categoria_id' => $electronica?->id,
                'catalogo_id' => $novedades?->id,
            ],
            [
                'codigo' => 'METAL001',
                'barcode' => '1234567890009',
                'nombre' => 'Master of Puppets - Metallica',
                'precio' => 365000,
                'cantidad' => 55,
                'descuento' => 0,
                'descripcion' => 'Tercer álbum de Metallica considerado una obra maestra del thrash metal',
                'imagen' => 'jj-image (3).png',
                'categoria_id' => $metal?->id,
                'catalogo_id' => $clasicos?->id,
            ],
            [
                'codigo' => 'METAL002',
                'barcode' => '1234567890010',
                'nombre' => 'Paranoid - Black Sabbath',
                'precio' => 310000,
                'cantidad' => 42,
                'descuento' => 10,
                'descripcion' => 'Segundo álbum de Black Sabbath con clásicos como Iron Man',
                'imagen' => 'n2-image (4).png',
                'categoria_id' => $metal?->id,
                'catalogo_id' => $clasicos?->id,
            ],
            [
                'codigo' => 'ROCK003',
                'barcode' => '1234567890011',
                'nombre' => 'Abbey Road - The Beatles',
                'precio' => 399900,
                'cantidad' => 70,
                'descuento' => 0,
                'descripcion' => 'Undécimo álbum de estudio de The Beatles',
                'imagen' => 'uh-image (5).png',
                'categoria_id' => $rock?->id,
                'catalogo_id' => $clasicos?->id,
            ],
            [
                'codigo' => 'ROCK004',
                'barcode' => '1234567890012',
                'nombre' => 'Nevermind - Nirvana',
                'precio' => 285000,
                'cantidad' => 65,
                'descuento' => 15,
                'descripcion' => 'Segundo álbum que definió el grunge de los 90',
                'imagen' => 'i8-NirvanaNevermindalbumcover.jpg',
                'categoria_id' => $rock?->id,
                'catalogo_id' => $ofertas?->id,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::firstOrCreate(
                ['codigo' => $producto['codigo']],
                $producto
            );
        }
    }
}
