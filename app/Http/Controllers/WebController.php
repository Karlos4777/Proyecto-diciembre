<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Catalogo;

class WebController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        // 🔍 Búsqueda por nombre
        if ($request->has('search') && $request->search) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // 🛒 Productos principales (para la vista general)
        $productos = $query->paginate(10);

        // 🆕 Lo más reciente (con eager loading)
        $productosRecientes = Producto::with(['categoria', 'catalogo'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // 💥 Lo más vendido (por catálogo - optimizado)
        $catalogos = Catalogo::with(['productos' => function ($q) {
            $q->with(['categoria'])
                ->orderBy('ventas', 'desc')
                ->take(10);
        }])->get();

        // Transformamos los catálogos en un array usable para la vista
        $productosVendidosPorCatalogo = [];
        foreach ($catalogos as $catalogo) {
            $productosVendidosPorCatalogo[$catalogo->nombre] = $catalogo->productos;
        }

        // 👀 Visto recientemente (guardado en sesión - con eager loading)
        $vistosRecientemente = [];
        if (session()->has('vistos_recientemente')) {
            $vistos = array_slice(session('vistos_recientemente'), 0, 10);
            $vistosRecientemente = Producto::with(['categoria', 'catalogo'])
                ->whereIn('id', $vistos)
                ->get();
        }

        // 📤 Enviamos todo a la vista
        return view('web.index', compact(
            'productos',
            'productosRecientes',
            'catalogos',
            'productosVendidosPorCatalogo',
            'vistosRecientemente'
        ));
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);

        // 🔁 Guardar en "vistos recientemente"
        $vistos = session('vistos_recientemente', []);
        if (!in_array($producto->id, $vistos)) {
            array_unshift($vistos, $producto->id);
            $vistos = array_slice($vistos, 0, 10); // máximo 10 productos
            session(['vistos_recientemente' => $vistos]);
        }

        return view('web.item', compact('producto'));
    }

public function buscarProductosAjax(Request $request)
{
    $search = trim((string) $request->get('search', ''));

    if ($search === '') {
        return response()->json([]);
    }

    // Dividir en palabras y filtrar vacíos
    $words = preg_split('/\s+/', $search);
    $words = array_filter(array_map('trim', $words), fn($w) => $w !== '');

    // Evitar seleccionar columnas que pueden no existir en todas las migraciones
    $productosQuery = Producto::with(['categoria', 'catalogo'])
        ->select('id', 'nombre', 'precio', 'imagen', 'cantidad', 'descuento', 'categoria_id', 'catalogo_id');

    // Requerir que cada palabra aparezca en el nombre, o en la categoría, o en el catálogo
    // usando COLLATE utf8mb4_general_ci para búsqueda insensible a acentos
    foreach ($words as $word) {
        $productosQuery->where(function ($q) use ($word) {
            $like = '%' . $word . '%';
            $q->where('nombre', 'like', $like)
                ->orWhereRaw("nombre COLLATE utf8mb4_general_ci LIKE ?", [$like])
                ->orWhereHas('categoria', function ($qc) use ($like) {
                    $qc->where('nombre', 'like', $like)
                        ->orWhereRaw("nombre COLLATE utf8mb4_general_ci LIKE ?", [$like]);
                })
                ->orWhereHas('catalogo', function ($qc) use ($like) {
                    $qc->where('nombre', 'like', $like)
                        ->orWhereRaw("nombre COLLATE utf8mb4_general_ci LIKE ?", [$like]);
                });
        });
    }

    $productos = $productosQuery->limit(10)->get()->map(function ($producto) {
        // Calcular precio con descuento si aplica (algunos esquemas no tienen columna `precio_con_descuento`)
        $precio = (float) $producto->precio;
        $descuento = (int) ($producto->descuento ?? 0);
        $precioConDescuento = $descuento > 0 ? round($precio * (100 - $descuento) / 100, 2) : $precio;

        return [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'precio' => $producto->precio,
            'imagen' => $producto->imagen ? asset('uploads/productos/' . $producto->imagen) : asset('img/sin-imagen.png'),
            'categoria' => $producto->categoria->nombre ?? 'Sin categoría',
            'catalogo' => $producto->catalogo->nombre ?? 'Sin catálogo',
            'estado' => ((int) $producto->cantidad) >= 21
                ? 'Disponible'
                : (((int) $producto->cantidad) >= 1 ? 'Pocas unidades' : 'Agotado'),
            'descuento' => $descuento,
            'precio_con_descuento' => $precioConDescuento,
        ];
    });

    return response()->json($productos);
}

}