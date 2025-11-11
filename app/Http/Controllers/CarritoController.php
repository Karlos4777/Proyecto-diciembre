<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Carrito;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{public function __construct()
    {
        $this->middleware('auth'); // Solo usuarios autenticados
    }

    // Mostrar carrito
    public function mostrar()
    {
        $registro = Carrito::firstOrCreate(
            ['user_id' => Auth::id()],
            ['contenido' => []]
        );

        $carrito = $registro->contenido;

        return view('web.pedido', compact('carrito'));
    }

    // Agregar producto
    public function agregar(Request $request)
    {
        $producto = Producto::findOrFail($request->producto_id);

        $registro = Carrito::firstOrCreate(
            ['user_id' => Auth::id()],
            ['contenido' => []]
        );

        $contenido = $registro->contenido;

        if(isset($contenido[$producto->id])){
            $contenido[$producto->id]['cantidad'] += 1;
        } else {
            $contenido[$producto->id] = [
                'nombre' => $producto->nombre,
                'codigo' => $producto->codigo,
                'precio' => $producto->precio,
                'cantidad' => 1,
                'imagen' => $producto->imagen,
            ];
        }

        $registro->contenido = $contenido;
        $registro->save();

        return redirect()->back()->with('mensaje', 'Producto agregado al carrito');
    }

    // Restar producto
    public function restar($producto_id)
    {
        $registro = Carrito::firstOrCreate(
            ['user_id' => Auth::id()],
            ['contenido' => []]
        );

        $contenido = $registro->contenido;

        if(isset($contenido[$producto_id])){
            $contenido[$producto_id]['cantidad']--;
            if($contenido[$producto_id]['cantidad'] <= 0){
                unset($contenido[$producto_id]);
            }
            $registro->contenido = $contenido;
            $registro->save();
        }

        return redirect()->back();
    }

    // Sumar producto
    public function sumar($producto_id)
    {
        $registro = Carrito::firstOrCreate(
            ['user_id' => Auth::id()],
            ['contenido' => []]
        );

        $contenido = $registro->contenido;

        if(isset($contenido[$producto_id])){
            $contenido[$producto_id]['cantidad']++;
            $registro->contenido = $contenido;
            $registro->save();
        }

        return redirect()->back();
    }

    // Eliminar producto
    public function eliminar($producto_id)
    {
        $registro = Carrito::firstOrCreate(
            ['user_id' => Auth::id()],
            ['contenido' => []]
        );

        $contenido = $registro->contenido;

        if(isset($contenido[$producto_id])){
            unset($contenido[$producto_id]);
            $registro->contenido = $contenido;
            $registro->save();
        }

        return redirect()->back();
    }

    // Vaciar carrito
    public function vaciar()
    {
        $registro = Carrito::firstOrCreate(
            ['user_id' => Auth::id()],
            ['contenido' => []]
        );

        $registro->contenido = [];
        $registro->save();

        return redirect()->back()->with('mensaje', 'Carrito vaciado correctamente');
    }
}