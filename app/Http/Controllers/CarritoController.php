<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Carrito;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
  public function mostrar()
{
    $registro = Carrito::where('user_id', auth()->id())->first();
    if ($registro) {
    $contenido = $registro->contenido;

    // Si el contenido ya es array, úsalo directamente
    if (is_array($contenido)) {
        $carrito = $contenido;
    } else {
        // Si es string JSON, decodifícalo
        $carrito = json_decode($contenido, true) ?? [];
    }
} else {
    $carrito = [];
}

    return view('web.pedido', compact('carrito'));
}

    
public function agregar(Request $request)
{
    $producto = Producto::findOrFail($request->producto_id);

    $carrito = session()->get('carrito', []);

    $carrito[$producto->id] = [
        'nombre' => $producto->nombre,
        'codigo' => $producto->codigo,
        'precio' => $producto->precio,
        'cantidad' => 1,
        'imagen' => $producto->imagen,
    ];

    session()->put('carrito', $carrito);

    return redirect()->back()->with('mensaje', 'Producto agregado al carrito');
}
    public function sumar(Request $request)
{
    $carrito = Carrito::where('user_id', Auth::id())->first();

    if (!$carrito) {
        return redirect()->back()->with('error', 'El carrito está vacío');
    }

    $contenido = $carrito->contenido;
    $productoId = $request->producto_id;

    if (isset($contenido[$productoId])) {
        $contenido[$productoId]['cantidad']++;
        $carrito->update(['contenido' => $contenido]);
    }

    return redirect()->back()->with('mensaje', 'Cantidad actualizada');
}

public function restar(Request $request)
{
    $carrito = Carrito::where('user_id', Auth::id())->first();

    if (!$carrito) {
        return redirect()->back()->with('error', 'El carrito está vacío');
    }

    $contenido = $carrito->contenido;
    $productoId = $request->producto_id;

    if (isset($contenido[$productoId])) {
        if ($contenido[$productoId]['cantidad'] > 1) {
            $contenido[$productoId]['cantidad']--;
        } else {
            unset($contenido[$productoId]);
        }
        $carrito->update(['contenido' => $contenido]);
    }

    return redirect()->back()->with('mensaje', 'Cantidad actualizada');
}

public function eliminar($id)
{
    $carrito = Carrito::where('user_id', Auth::id())->first();

    if (!$carrito) {
        return redirect()->back()->with('error', 'El carrito está vacío');
    }

    $contenido = $carrito->contenido;
    unset($contenido[$id]);
    $carrito->update(['contenido' => $contenido]);

    return redirect()->back()->with('mensaje', 'Producto eliminado del carrito');
}

public function vaciar()
{
    $carrito = Carrito::where('user_id', Auth::id())->first();

    if ($carrito) {
        $carrito->update(['contenido' => []]);
    }

    return redirect()->back()->with('mensaje', 'Carrito vaciado correctamente');
}
public static function contarProductos()
{
    if (Auth::check()) {
        $carrito = Carrito::where('user_id', Auth::id())->first();
        return count($carrito->contenido ?? []);
    }
    return 0;
}

}