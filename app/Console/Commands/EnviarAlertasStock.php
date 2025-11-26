<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockBajoNotificacion;

class EnviarAlertasStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:alertar-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía alertas por email cuando hay productos con stock bajo o agotados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando stock de productos...');

        // Obtener productos con stock crítico
        $productosCriticos = Producto::where('cantidad', '>', 0)
            ->where('cantidad', '<', 21)
            ->get();

        // Obtener productos agotados
        $productosAgotados = Producto::where('cantidad', 0)->get();

        if ($productosCriticos->isEmpty() && $productosAgotados->isEmpty()) {
            $this->info('✓ No hay productos con stock bajo. Todo en orden.');
            return 0;
        }

        $this->warn('⚠ Se encontraron productos con problemas de stock:');
        $this->info('  - Productos críticos: ' . $productosCriticos->count());
        $this->info('  - Productos agotados: ' . $productosAgotados->count());

        // Enviar email a administradores
        try {
            $admins = User::role('Admin')->get();
            
            if ($admins->isEmpty()) {
                $this->error('✗ No se encontraron administradores para notificar.');
                return 1;
            }

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(
                    new StockBajoNotificacion($productosCriticos, $productosAgotados)
                );
                $this->info('✓ Email enviado a: ' . $admin->email);
            }

            $this->info('✓ Alertas de stock enviadas exitosamente.');
            return 0;

        } catch (\Exception $e) {
            $this->error('✗ Error al enviar emails: ' . $e->getMessage());
            \Log::error('Error en EnviarAlertasStock: ' . $e->getMessage());
            return 1;
        }
    }
}
