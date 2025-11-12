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

        // 🔀 Filtro de orden (precio asc/desc)
        if ($request->has('sort') && $request->sort) {
            switch ($request->sort) {
                case 'priceAsc':
                    $query->orderBy('precio', 'asc');
                    break;
                case 'priceDesc':
                    $query->orderBy('precio', 'desc');
                    break;
                default:
                    $query->orderBy('nombre', 'asc');
                    break;
            }
        }

        // 🛒 Productos principales (para la vista general)
        $productos = $query->paginate(10);

        // 🆕 Lo más reciente
        $productosRecientes = Producto::orderBy('created_at', 'desc')->take(10)->get();

        // 💥 Lo más vendido (por catálogo)
        $catalogos = Catalogo::with(['productos' => function ($q) {
            $q->orderBy('ventas', 'desc')->take(10);
        }])->get();

        // Transformamos los catálogos en un array usable para la vista
        $productosVendidosPorCatalogo = [];
        foreach ($catalogos as $catalogo) {
            $productosVendidosPorCatalogo[$catalogo->nombre] = $catalogo->productos;
        }

        // 👀 Visto recientemente (guardado en sesión)
        $vistosRecientemente = [];
        if (session()->has('vistos_recientemente')) {
            $vistosRecientemente = Producto::whereIn('id', session('vistos_recientemente'))->get();
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
}
