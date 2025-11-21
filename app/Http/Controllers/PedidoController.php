<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\PedidoReferencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Carrito;

class PedidoController extends Controller
{public function index(Request $request)
{
    $texto = trim($request->get('texto', ''));
    
    // Construir la consulta base
    $query = Pedido::with(['user', 'detalles.producto', 'referencias'])->orderBy('created_at', 'desc');

    // Permisos
    if (auth()->user()->can('pedido-list')) {
        // Admin: puede ver todos los pedidos
        $view = 'pedido.index';
    } elseif (auth()->user()->can('pedido-view')) {
        // Cliente: solo puede ver sus propios pedidos
        $query->where('user_id', auth()->id());
        $view = 'web.mis_pedidos';
    } else {
        abort(403, 'No tienes permisos para ver pedidos.');
    }

    // Búsqueda
    if (!empty($texto)) {
        $query->where(function ($q) use ($texto) {
            $q->where('id', 'like', "%{$texto}%")
              ->orWhereHas('user', function ($subQ) use ($texto) {
                  $subQ->where('name', 'like', "%{$texto}%");
              });
        });
    }

    $registros = $query->paginate(10);

    return view($view, compact('registros', 'texto'));
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
        return redirect()->back()->with('error', 'El carrito está vacío.');
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
            'fecha' => now()->toDateString(),
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

    // Enviar mensaje de éxito sin HTML para evitar que aparezcan caracteres sueltos
    return redirect()->route('web.index')->with('success', '¡Compra exitosa! Tu pedido #' . $pedido->id . ' ha sido registrado.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al procesar pedido: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Hubo un error al procesar el pedido: ' . $e->getMessage());
    }
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

    // Subir referencia (archivo) asociada a un pedido
    public function uploadReferencia(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        // Permitir que solo el propietario o admin suba (clientes y admins)
        if (!auth()->user()->can('pedido-list') && $pedido->user_id !== auth()->id()) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'archivo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB
        ]);

        $file = $request->file('archivo');
        $path = $file->storeAs('pedidos/' . $pedido->id, time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName()), 'public');

        $referencia = PedidoReferencia::create([
            'pedido_id' => $pedido->id,
            'user_id' => auth()->id(),
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()->back()->with('mensaje', 'Referencia subida correctamente.');
    }
}