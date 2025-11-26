<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\PedidoReferencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Carrito;
use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoConfirmacion;
use App\Mail\NuevoPedidoAdmin;
use App\Models\User;

class PedidoController extends Controller
{public function index(Request $request)
{
    $texto = trim($request->get('texto', ''));
    
    // Construir la consulta base
    // Usar 'detalles.producto.categoria' para asegurar que en la vista web.mis_pedidos
    // $pedido->detalles tenga cargado el producto y su categoría (eager loading)
    $query = Pedido::with(['user', 'detalles.producto.categoria', 'referencias'])->orderBy('created_at', 'desc');

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

    // Debug logging para diagnosticar por qué clientes no ven sus pedidos
    try {
        \Log::debug("PedidoController@index debug", [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email ?? null,
            'view' => $view,
            'registros_count' => is_object($registros) && method_exists($registros, 'count') ? $registros->count() : (is_array($registros) ? count($registros) : null),
        ]);
    } catch (\Throwable $e) {
        // no detener la ejecución por fallos en logging
        \Log::error('Error al escribir debug en PedidoController@index: ' . $e->getMessage());
    }

    // Additional per-pedido debug: lineas and producto availability
    try {
        $pedidos_debug = [];
            foreach ($registros as $p) {
                $lineas_count = is_object($p->detalles) && method_exists($p->detalles, 'count') ? $p->detalles->count() : (is_array($p->detalles) ? count($p->detalles) : 0);
                $sample = [];
                if ($lineas_count > 0) {
                    $i = 0;
                    foreach ($p->detalles as $d) {
                        if ($i++ >= 3) break;
                        $sample[] = [
                            'detalle_id' => $d->id ?? null,
                            'producto_id' => $d->producto_id ?? null,
                            'producto_loaded' => $d->producto ? true : false,
                            'producto_nombre' => $d->producto->nombre ?? null,
                        ];
                    }
                }
                $pedidos_debug[] = [
                    'pedido_id' => $p->id,
                    'lineas_count' => $lineas_count,
                    'lineas_sample' => $sample,
                ];
        }
        \Log::debug('PedidoController@index pedidos_debug', ['pedidos' => $pedidos_debug]);
    } catch (\Throwable $e) {
        \Log::error('Error building pedidos_debug: ' . $e->getMessage());
    }

    return view($view, compact('registros', 'texto'));
}
        public function formulario()
{
    $registro = Carrito::firstOrCreate(
        ['user_id' => auth()->id()],
        ['contenido' => []]
    );

    $carrito = $registro->contenido ?? [];

        // Log carrito contents for debugging (non-fatal)
        try {
            \Log::debug('PedidoController@formulario debug', [
                'user_id' => auth()->id(),
                'carrito_count' => is_array($carrito) ? count($carrito) : (is_object($carrito) && method_exists($carrito, 'count') ? $carrito->count() : null),
                'carrito_sample' => array_slice(is_array($carrito) ? $carrito : [], 0, 5),
            ]);
        } catch (\Throwable $e) {
            // don't break the flow if logging fails
            \Log::error('Error logging carrito in PedidoController@formulario: ' . $e->getMessage());
        }

        if (empty($carrito)) {
            // keep the redirect behavior but include a flash so the UI shows why
            return redirect()->route('carrito.mostrar')->with('error', 'El carrito está vacío. Agrega productos antes de completar la compra.');
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
        'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
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

        // Si se subió un archivo en el formulario, guardarlo y crear la referencia
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('pedidos/' . $pedido->id, $safeName, 'public');

            PedidoReferencia::create([
                'pedido_id' => $pedido->id,
                'user_id' => auth()->id(),
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        // Vaciar carrito del usuario en la base de datos
        $registro->contenido = [];
        $registro->save();

        // Enviar email de confirmación al cliente
        try {
            Mail::to($pedido->user->email)->send(new PedidoConfirmacion($pedido));
        } catch (\Exception $e) {
            \Log::error('Error al enviar email de confirmación: ' . $e->getMessage());
        }

        // Enviar notificación a administradores
        try {
            $admins = User::role('Admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NuevoPedidoAdmin($pedido));
            }
        } catch (\Exception $e) {
            \Log::error('Error al enviar email a administradores: ' . $e->getMessage());
        }

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
            'detalle_id' => 'nullable|integer|exists:pedido_detalles,id'
        ]);

        // Si se envió un detalle_id, asegurar que pertenece al pedido
        $detalleId = $request->input('detalle_id');
        if ($detalleId) {
            $detalle = PedidoDetalle::find($detalleId);
            if (!$detalle || $detalle->pedido_id != $pedido->id) {
                return redirect()->back()->with('error', 'El detalle seleccionado no pertenece a este pedido.');
            }
        }

        $file = $request->file('archivo');
        $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('pedidos/' . $pedido->id, $safeName, 'public');

        $referencia = PedidoReferencia::create([
            'pedido_id' => $pedido->id,
            'user_id' => auth()->id(),
            'detalle_id' => $detalleId ?? null,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()->back()->with('mensaje', 'Referencia subida correctamente.');
    }
}