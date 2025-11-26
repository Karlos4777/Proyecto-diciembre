<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Catalogo;
use App\Models\Carrito;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $categoriaId = $request->input('categoria_id');
        $catalogoId = $request->input('catalogo_id');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->startOfMonth();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        $pedidosQuery = Pedido::whereBetween('fecha', [$fromDate, $toDate]);
        $pedidos = $pedidosQuery->get();

        $ventasTotal = (float) $pedidos->sum('total');
        $pedidosCount = (int) $pedidos->count();
        $unidadesVendidas = (int) $pedidos->sum(function($p){
            $contenido = $p->contenido ?? [];
            if (is_array($contenido)) {
                return collect($contenido)->sum('cantidad');
            }
            return 0;
        });
        $ticketPromedio = $pedidosCount > 0 ? round($ventasTotal / $pedidosCount, 2) : 0.0;

        // Top productos por ventas (usando contenido del pedido si existe)
        $ventasPorProducto = [];
        foreach ($pedidos as $pedido) {
            $contenido = $pedido->contenido ?? [];
            if (is_array($contenido)) {
                foreach ($contenido as $linea) {
                    $pid = $linea['producto_id'] ?? null;
                    $qty = (int) ($linea['cantidad'] ?? 0);
                    if ($pid) {
                        $ventasPorProducto[$pid] = ($ventasPorProducto[$pid] ?? 0) + $qty;
                    }
                }
            }
        }
        arsort($ventasPorProducto);
        $topProductoIds = array_slice(array_keys($ventasPorProducto), 0, 5);
        $topProductos = Producto::whereIn('id', $topProductoIds)->get();

        // Stock crítico
        $stockCritico = Producto::where('cantidad', '>', 0)->where('cantidad', '<', 21);
        $agotados = Producto::where('cantidad', 0);
        if ($categoriaId) {
            $stockCritico->where('categoria_id', $categoriaId);
            $agotados->where('categoria_id', $categoriaId);
        }
        if ($catalogoId) {
            $stockCritico->where('catalogo_id', $catalogoId);
            $agotados->where('catalogo_id', $catalogoId);
        }

        // Categorías y catálogos para los selects
        $categorias = Categoria::orderBy('nombre')->get();
        $catalogos = Catalogo::orderBy('nombre')->get();

        // Ventas por día para gráfica (últimos 30 días)
        $ventasPorDia = [];
        $ultimosDias = 30;
        for ($i = $ultimosDias - 1; $i >= 0; $i--) {
            $dia = Carbon::now()->subDays($i);
            $ventasDia = Pedido::whereDate('fecha', $dia->toDateString())->sum('total');
            $ventasPorDia[] = [
                'fecha' => $dia->format('d/m'),
                'total' => (float) $ventasDia
            ];
        }

        // Carritos abandonados (con contenido y sin pedido reciente)
        $carritosAbandonados = Carrito::whereNotNull('contenido')
            ->where('updated_at', '<', Carbon::now()->subHours(24))
            ->with('user')
            ->take(10)
            ->get()
            ->filter(function($carrito) {
                if (!$carrito->user) return false;
                $ultimoPedido = Pedido::where('user_id', $carrito->user_id)
                    ->where('created_at', '>', $carrito->updated_at)
                    ->first();
                return !$ultimoPedido;
            });

        // Distribución por categoría (productos vendidos)
        $ventasPorCategoria = [];
        foreach ($pedidos as $pedido) {
            $contenido = $pedido->contenido ?? [];
            if (is_array($contenido)) {
                foreach ($contenido as $linea) {
                    $pid = $linea['producto_id'] ?? null;
                    if ($pid) {
                        $producto = Producto::find($pid);
                        if ($producto && $producto->categoria) {
                            $catName = $producto->categoria->nombre;
                            $qty = (int) ($linea['cantidad'] ?? 0);
                            $ventasPorCategoria[$catName] = ($ventasPorCategoria[$catName] ?? 0) + $qty;
                        }
                    }
                }
            }
        }
        arsort($ventasPorCategoria);

        return view('dashboard', [
            'metrics' => [
                'ventasTotal' => $ventasTotal,
                'pedidosCount' => $pedidosCount,
                'unidadesVendidas' => $unidadesVendidas,
                'ticketPromedio' => $ticketPromedio,
            ],
            'topProductos' => $topProductos,
            'ventasPorProducto' => $ventasPorProducto,
            'stockCritico' => $stockCritico->take(10)->get(),
            'agotados' => $agotados->take(10)->get(),
            'categorias' => $categorias,
            'catalogos' => $catalogos,
            'ventasPorDia' => $ventasPorDia,
            'carritosAbandonados' => $carritosAbandonados,
            'ventasPorCategoria' => $ventasPorCategoria,
            'filters' => [
                'from' => $fromDate->toDateString(),
                'to' => $toDate->toDateString(),
                'categoria_id' => $categoriaId,
                'catalogo_id' => $catalogoId,
            ]
        ]);
    }
}
