<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;

class BuscarProductosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function buscar_productos_ajax_devuelve_json_esperado()
    {
        // Crear productos de prueba
        Producto::create([
            'nombre' => 'Disco Azul',
            'precio' => 1200,
            'cantidad' => 10,
            'descuento' => 10,
        ]);

        Producto::create([
            'nombre' => 'Vinilo Rojo',
            'precio' => 850,
            'cantidad' => 5,
            'descuento' => 0,
        ]);

        $response = $this->getJson(route('buscar.ajax', ['search' => 'Disco']));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id','nombre','precio','imagen','categoria','catalogo','estado','descuento','precio_con_descuento']
        ]);
    }

    /** @test */
    public function busqueda_por_varias_palabras_retorna_resultados()
    {
        Producto::create([
            'nombre' => 'Caja de Vinilos Clasicos',
            'precio' => 2000,
            'cantidad' => 3,
            'descuento' => 15,
        ]);

        $response = $this->getJson(route('buscar.ajax', ['search' => 'Vinilos Clasicos']));
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json());
    }
}   