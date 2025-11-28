<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Producto;
use Carbon\Carbon;

class PedidosSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el usuario cliente
        $cliente = User::where('email', 'cliente@prueba.com')->first();
        
        if (!$cliente) {
            $this->command->error('Usuario cliente no encontrado');
            return;
        }

        // Obtener productos con sus categorías
        $productos = Producto::with('categoria')->take(5)->get();

        if ($productos->count() === 0) {
            $this->command->error('No hay productos en la base de datos');
            return;
        }

        // Crear 3 pedidos de ejemplo
        for ($i = 1; $i <= 3; $i++) {
            $productosPedido = $productos->random(rand(2, 4));
            $total = 0;

            $pedido = Pedido::create([
                'user_id' => $cliente->id,
                'total' => 0, // Se calculará después
                'estado' => $i === 1 ? 'pendiente' : ($i === 2 ? 'enviado' : 'pendiente'),
                'fecha' => Carbon::now()->subDays(rand(0, 10)),
            ]);

            foreach ($productosPedido as $producto) {
                $cantidad = rand(1, 3);
                $precio = $producto->precio;
                $subtotal = $precio * $cantidad;
                $total += $subtotal;

                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                ]);
            }

            // Actualizar el total del pedido
            $pedido->update(['total' => $total]);

            $this->command->info("Pedido #{$pedido->id} creado con {$productosPedido->count()} productos. Total: \${$total}");
        }

        $this->command->info('✅ Pedidos de prueba creados exitosamente');
    }
}
