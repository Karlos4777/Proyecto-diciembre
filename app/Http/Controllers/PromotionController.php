<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class PromotionController extends Controller
{
    /**
     * Asigna o actualiza un descuento porcentual a un producto.
     * Espera: producto_id y descuento (0-100)
     */
    public function assign(Request $request)
    {
        $data = $request->validate([
            'producto_id' => 'required|integer|exists:productos,id',
            'descuento' => 'required|integer|min:0|max:100',
        ]);

        $producto = Producto::findOrFail($data['producto_id']);
        $producto->descuento = (int) $data['descuento'];
        $producto->save();

        return redirect()->back()->with('mensaje', 'Descuento actualizado correctamente');
    }
}
