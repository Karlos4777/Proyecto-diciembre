<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Producto;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Wishlist::with('producto')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('web.favoritos', compact('items'));
    }

    public function toggle(Producto $producto)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $existing = Wishlist::where('user_id', $userId)
            ->where('producto_id', $producto->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('mensaje', 'Producto eliminado de tus favoritos.');
        }

        Wishlist::create([
            'user_id' => $userId,
            'producto_id' => $producto->id,
        ]);
        return back()->with('mensaje', 'Producto agregado a tus favoritos.');
    }
}
