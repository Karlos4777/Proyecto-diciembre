<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Carrito;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    // Mostrar el carrito del usuario autenticado
    public function mostrar()
    {
        $registro = Carrito::where('user_id', Auth::id())->first();

        $carrito = $registro ? $registro->contenido : [];

        return view('web.pedido', compact('carrito'));
    }

    // Agregar producto al carrito
    public function agregar(Request $request)
    {
        $producto = Producto::findOrFail($request->producto_id);
        $cantidad = $request->cantidad ?? 1;

        $carrito = Carrito::firstOrCreate(['user_id' => Auth::id()], ['contenido' => []]);

        $contenido = $carrito->contenido ?? [];

        if (isset($contenido[$producto->id])) {
            $contenido[$producto->id]['cantidad'] += $cantidad;
        } else {
            $contenido[$producto->id] = [
                'codigo' => $producto->codigo,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'imagen' => $producto->imagen,
                'cantidad' => $cantidad,
            ];
        }

        $carrito->update(['contenido' => $contenido]);

        return redirect()->back()->with('mensaje', 'Producto agregado al carrito');
    }

public function sumar(Request $request)
{
    // Buscar el carrito del usuario autenticado
    $carrito = Carrito::where('user_id', Auth::id())->first();

    // Si no existe carrito, redirigir
    if (!$carrito) return redirect()->back()->with('error', 'No se encontró el carrito');

    // Obtener el contenido (array)
    $contenido = $carrito->contenido;
    $productoId = $request->producto_id;

    // Verificar que el producto exista en el carrito
    if (isset($contenido[$productoId])) {
        $contenido[$productoId]['cantidad']++;
        $carrito->update(['contenido' => $contenido]);
    }

    return redirect()->back()->with('mensaje', 'Cantidad actualizada');
}
    

    // Restar cantidad
    public function restar(Request $request)
    {
        $registro = Carrito::where('user_id', Auth::id())->first();
        if (!$carrito) return redirect()->back()->with('error', 'No se encontró el carrito');

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

    // Eliminar producto
    public function eliminar($id)
    {
        $registro = Carrito::where('user_id', Auth::id())->first();
        if (!$carrito) return redirect()->back()->with('error', 'No se encontró el carrito');

        $contenido = $carrito->contenido;
        unset($contenido[$id]);

        $carrito->update(['contenido' => $contenido]);

        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }

    // Vaciar carrito
    public function vaciar()
    {
        Carrito::where('user_id', Auth::id())->update(['contenido' => []]);
        return redirect()->back()->with('success', 'Carrito vaciado');
    }
}