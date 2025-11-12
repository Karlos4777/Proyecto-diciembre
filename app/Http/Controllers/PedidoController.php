<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PedidoController extends Controller
{public function index(Request $request)
{
    $texto = $request->input('texto');
    $texto = trim($request->get('texto', ''));
    $query = Pedido::with('user', 'detalles.producto')->orderBy('id', 'desc');
    $registros = Pedido::with(['user', 'detalles.producto'])
            ->whereHas('user', function ($q) use ($texto) {
                $q->where('name', 'like', "%$texto%");
            })
            ->orWhere('id', 'like', "%$texto%")
            ->orderBy('created_at', 'desc');

    // Permisos
    if (auth()->user()->can('pedido-list')) {
        // Puede ver todos los pedidos
    } elseif (auth()->user()->can('pedido-view')) {
        // Solo puede ver sus propios pedidos
        $query->where('user_id', auth()->id());
    } else {
        abort(403, 'No tienes permisos para ver pedidos.');
    }

    // Búsqueda
    if (!empty($texto)) {
        $query->whereHas('user', function ($q) use ($texto) {
            $q->where('name', 'like', "%{$texto}%");
        });
    }

    $registros = $query->paginate(10);

    return view('pedido.index', compact('registros', 'texto'));
}
        public function formulario()
{
    $registro = Carrito::firstOrCreate(
        ['user_id' => auth()->id()],
        ['contenido' => []]
    );

    $carrito = $registro->contenido ?? [];

    if (empty($carrito)) {
        return redirect()->route('carrito.mostrar')->with('error', 'El carrito está vacío.');
    }

    return view('web.formulario_pedido', compact('carrito'));
}

public function realizar(Request $request)
{
    $registro = Carrito::firstOrCreate(
        ['user_id' => auth()->id()],
        ['contenido' => []]
    );

    $carrito = $registro->contenido ?? [];

    // Validación
    $request->validate([
        'nombre' => 'required',
        'email' => 'required|email',
        'telefono' => 'required',
        'direccion' => 'required',
        'metodo_pago' => 'required',
    ]);

    if (empty($carrito)) {
        return redirect()->back()->with('mensaje', 'El carrito está vacío.');
    }

    DB::beginTransaction();

    try {
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        // Crear pedido
        $pedido = Pedido::create([
            'user_id' => auth()->id(),
            'total' => $total,
            'estado' => 'pendiente',
        ]);

        foreach ($carrito as $productoId => $item) {
            PedidoDetalle::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $productoId,
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio'],
            ]);
        }

        // Vaciar carrito del usuario en la base de datos
        $registro->contenido = [];
        $registro->save();

        DB::commit();

        return redirect()->route('web.index')->with('success', 'Pedido realizado con éxito.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Hubo un error al procesar el pedido.');
    }
}
  public function store(Request $request)
    {
        $user = auth()->user();

        $pedido = Pedido::create([
            'user_id' => $user->id,
            'total' => $request->input('total'),
            'estado' => 'pendiente',
        ]);

        // Guardar detalles
        foreach ($request->input('carrito', []) as $item) {
            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio'],
            ]);
        }

        return redirect()->route('pedido.index')->with('mensaje', 'Pedido registrado correctamente');
    }

    // Cambiar estado del pedido
    public function cambiarEstado(Request $request, $id){
        $pedido = Pedido::findOrFail($id);
        $estadoNuevo = $request->input('estado');

        // Validar que el estado nuevo sea uno permitido
        $estadosPermitidos = ['enviado', 'anulado', 'cancelado'];

        if (!in_array($estadoNuevo, $estadosPermitidos)) {
            abort(403, 'Estado no válido');
        }

        // Verificar permisos según el estado
        if (in_array($estadoNuevo, ['enviado', 'anulado'])) {
            if (!auth()->user()->can('pedido-anulate')) {
                abort(403, 'No tiene permiso para cambiar a "enviado" o "anulado"');
            }
        }

        if ($estadoNuevo === 'cancelado') {
            if (!auth()->user()->can('pedido-cancel')) {
                abort(403, 'No tiene permiso para cancelar pedidos');
            }
        }

        // Cambiar el estado
        $pedido->estado = $estadoNuevo;
        $pedido->save();

        return redirect()->back()->with('mensaje', 'El estado del pedido fue actualizado a "' . ucfirst($estadoNuevo) . '"');
    }
}