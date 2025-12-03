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

        $contenido = $registro->contenido ?? [];
        $total = 0;

        foreach ($contenido as $productoId => &$item) {
            $producto = Producto::with('categoria')->find($productoId);

            // Si el producto ya no existe, lo eliminamos del carrito
            if (!$producto) {
                unset($contenido[$productoId]);
                continue;
            }

            // Actualizar datos básicos (nombre, codigo, imagen, categoria)
            $item['nombre'] = $producto->nombre;
            $item['codigo'] = $producto->codigo;
            $item['imagen'] = $producto->imagen;
            $item['categoria'] = $producto->categoria ? $producto->categoria->nombre : 'Sin categoría';
            $item['categoria_id'] = $producto->categoria ? $producto->categoria->id : null;

            // Cantidad segura
            $item['cantidad'] = isset($item['cantidad']) ? (int) $item['cantidad'] : 1;

            // Precios: original y con descuento si aplica
            $item['precio_original'] = $producto->precio;
            $item['precio'] = $producto->precio_con_descuento ?? $producto->precio;

            // Subtotal por línea
            $item['subtotal'] = round($item['precio'] * $item['cantidad'], 2);

            $total += $item['subtotal'];
        }
        unset($item);

        // Guardar contenido actualizado (precios, nombres) en el registro para mantener consistencia
        $registro->contenido = $contenido;
        $registro->save();

        // Lógica de Puntos
        $puntosCanjeados = session('puntos_canjeados', 0);
        $descuentoPuntos = 0;
        
        if ($puntosCanjeados > 0) {
            // Tasa de conversión: 1 punto = $100 COP
            $descuentoPuntos = $puntosCanjeados * 100;
            
            // Validar que el descuento no exceda el total
            if ($descuentoPuntos > $total) {
                $descuentoPuntos = $total;
                // Ajustar puntos canjeados si excede el total (opcional, por ahora simple)
            }
        }

        $totalConDescuento = max(0, $total - $descuentoPuntos);

        $carrito = $contenido;

        return view('web.pedido', compact('carrito', 'total', 'puntosCanjeados', 'descuentoPuntos', 'totalConDescuento'));
    }

    // Canjear puntos
    public function canjearPuntos(Request $request)
    {
        $request->validate([
            'puntos' => 'required|integer|min:1'
        ]);

        $puntosUsuario = Auth::user()->puntos;
        $puntosAUsar = $request->input('puntos');

        if ($puntosAUsar > $puntosUsuario) {
            return redirect()->back()->with('error', 'No tienes suficientes puntos.');
        }

        // Guardar en sesión
        session(['puntos_canjeados' => $puntosAUsar]);

        return redirect()->back()->with('mensaje', 'Puntos aplicados correctamente.');
    }

    // Quitar puntos
    public function quitarPuntos()
    {
        session()->forget('puntos_canjeados');
        return redirect()->back()->with('mensaje', 'Puntos removidos.');
    }

    // Agregar producto
    public function agregar(Request $request)
    {
        $producto = Producto::with('categoria')->findOrFail($request->producto_id);

        $registro = Carrito::firstOrCreate(
            ['user_id' => Auth::id()],
            ['contenido' => []]
        );

        $contenido = $registro->contenido;

        if(isset($contenido[$producto->id])){
            $contenido[$producto->id]['cantidad'] += 1;
            // Asegurar que el precio almacenado esté actualizado si cambió el descuento
            $contenido[$producto->id]['precio_original'] = $producto->precio;
            $contenido[$producto->id]['precio'] = $producto->precio_con_descuento ?? $producto->precio;
        } else {
            $contenido[$producto->id] = [
                'nombre' => $producto->nombre,
                'codigo' => $producto->codigo,
                'categoria' => $producto->categoria ? $producto->categoria->nombre : 'Sin categoría',
                // Guardar precio original y precio con descuento (si aplica)
                'precio_original' => $producto->precio,
                'precio' => $producto->precio_con_descuento ?? $producto->precio,
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

        return redirect()->back()->with('mensaje', 'El carrito ha sido actualizado correctamente.');
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

        return redirect()->back()->with('mensaje', 'El carrito ha sido actualizado correctamente.');
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