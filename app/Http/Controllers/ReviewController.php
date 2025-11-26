<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Review;
use App\Models\PedidoDetalle;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Producto $producto)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000'
        ]);

        // Verificar compra previa
        $comprado = PedidoDetalle::where('producto_id', $producto->id)
            ->whereHas('pedido', function($q){
                $q->where('user_id', auth()->id());
            })
            ->exists();

        if(!$comprado){
            return back()->with('error', 'Debes haber comprado este producto para reseñarlo.');
        }

        // Evitar duplicados
        $existe = Review::where('user_id', auth()->id())
            ->where('producto_id', $producto->id)->first();
        if($existe){
            return back()->with('error', 'Ya has reseñado este producto.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'producto_id' => $producto->id,
            'rating' => (int)$request->rating,
            'comentario' => $request->comentario,
            'aprobado' => true,
        ]);

        return back()->with('mensaje', '¡Reseña registrada correctamente!');
    }

    public function destroy(Review $review)
    {
        if($review->user_id !== auth()->id() && !auth()->user()->can('producto-list')){
            abort(403);
        }
        $review->delete();
        return back()->with('mensaje', 'Reseña eliminada.');
    }
}
