@extends('web.app')

@section('contenido')

<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="section-title mb-4">
                <i class="bi bi-bag-check me-2"></i> Mis Pedidos
            </h2>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill me-3 fs-4 text-success flex-shrink-0 mt-1"></i>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">¡Compra exitosa! 🎉</h5>
                            <p class="mb-0">{{ session('success') }}</p>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle me-1"></i>
                                Tu pedido está marcado como <strong>NUEVO</strong> abajo
                            </small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Error:</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    @if($registros && $registros->count() > 0)
        <div class="row">
            @foreach($registros as $pedido)
                <div class="col-md-12 mb-4">
                    <!-- Destacar el primer pedido (el más reciente) cuando hay mensaje de éxito -->
                    <div class="card shadow-lg border-0 @if(session('success') && $loop->first) border-success border-3 @endif">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #6F4E37; color: white;">
                            <div>
                                <h5 class="mb-0">
                                    <i class="bi bi-file-text me-2"></i>Pedido #{{ $pedido->id }}
                                    @if(session('success') && $loop->first)
                                        <span class="badge bg-warning text-dark ms-2">
                                            <i class="bi bi-star-fill me-1"></i>NUEVO
                                        </span>
                                    @endif
                                </h5>
                                <small style="color: #e0e0e0;">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div>
                                @php
                                    $colores = [
                                        'pendiente' => 'bg-warning text-dark',
                                        'enviado' => 'bg-success',
                                        'anulado' => 'bg-danger',
                                        'cancelado' => 'bg-secondary',
                                    ];
                                @endphp
                                <span class="badge {{ $colores[$pedido->estado] ?? 'bg-dark' }} fs-6 shadow-sm">
                                    <i class="bi bi-circle-fill me-1"></i>{{ ucfirst($pedido->estado) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body" style="background-color: #ffffff;">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <h6 class="mb-3" style="color: #6F4E37;">Productos:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead style="background-color: #4A2F1E; color: white;">
                                                <tr>
                                                    <th class="py-2">Imagen</th>
                                                    <th class="py-2">Producto</th>
                                                    <th class="py-2 text-center">Código</th>
                                                    <th class="py-2 text-center">Categoría</th>
                                                    <th class="py-2 text-center">Cantidad</th>
                                                    <th class="py-2 text-end">Precio Unit.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($pedido->detalles && $pedido->detalles->count() > 0)
                                                    @foreach($pedido->detalles as $detalle)
                                                        <tr>
                                                            <td>
                                                                @if($detalle->producto && $detalle->producto->imagen)
                                                                    <img src="{{ asset('uploads/productos/' . $detalle->producto->imagen) }}" 
                                                                         alt="{{ $detalle->producto->nombre ?? 'Producto' }}" 
                                                                         class="rounded shadow-sm" 
                                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm" style="width: 50px; height: 50px;">
                                                                        <i class="bi bi-image fs-5 text-muted"></i>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <strong style="color: #4A2F1E;">{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-light text-dark border">{{ $detalle->producto->codigo ?? '-' }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                @if($detalle->producto && $detalle->producto->categoria)
                                                                    <span class="badge" style="background-color: #efe3d8; color: #6F4E37;">{{ $detalle->producto->categoria->nombre }}</span>
                                                                @else
                                                                    <small>-</small>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <strong>{{ $detalle->cantidad }}</strong>
                                                            </td>
                                                            <td class="text-end">
                                                                <strong style="color: #28a745;">${{ number_format($detalle->precio ?? 0, 2) }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-3">No hay detalles para este pedido</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm" style="background-color: #fbfaf9;">
                                        <div class="card-body">
                                            <h6 class="card-title" style="color: #6F4E37;">Resumen del pedido</h6>
                                            <hr style="border-color: #6F4E37; opacity: 0.2;">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <span>${{ number_format($pedido->total, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Envío:</span>
                                                <span class="badge bg-success">Gratis</span>
                                            </div>
                                            
                                            <div class="mt-3 p-3 rounded" style="background-color: #ffffff; border: 1px dashed #6F4E37;">
                                                <form action="{{ route('pedidos.referencia.upload', $pedido->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <label class="form-label mb-1 small fw-bold" style="color: #4A2F1E;">Subir comprobante general</label>
                                                    <div class="d-flex flex-column gap-2">
                                                        <input type="file" name="archivo" accept="image/*,.pdf" class="form-control form-control-sm" required>
                                                        <button class="btn btn-sm btn-outline-brown w-100" type="submit">
                                                            <i class="bi bi-cloud-upload me-1"></i> Subir Recibo
                                                        </button>
                                                    </div>
                                                    <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Tipos: jpg, png, pdf. Máx 5 MB.</small>
                                                </form>
                                            </div>
                                            
                                            <hr style="border-color: #6F4E37; opacity: 0.2;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong style="color: #4A2F1E;">Total:</strong>
                                                <strong class="fs-4" style="color: #6F4E37;">${{ number_format($pedido->total, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: #fbfaf9; border-top: 1px solid #eee;">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>{{ $pedido->created_at->format('d \d\e M \d\e Y') }}
                            </small>
                            @if($pedido->estado === 'pendiente')
                                <form action="{{ route('pedidos.cambiar.estado', $pedido->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="estado" value="cancelado">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('¿Estás seguro de cancelar este pedido?')">
                                        <i class="bi bi-trash me-1"></i> Cancelar Pedido
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="row mt-4">
            <div class="col-md-12">
                <nav aria-label="Page navigation">
                    {{ $registros->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <h5>No tienes pedidos aún</h5>
            <p class="text-muted mb-3">Una vez realices tu primera compra, aparecerá aquí.</p>
            <a href="{{ route('web.index') }}" class="btn btn-primary">
                <i class="bi bi-shop me-2"></i>Continuar comprando
            </a>
        </div>
    @endif

    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <a href="{{ route('web.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver a la tienda
            </a>
            <a href="{{ route('carrito.mostrar') }}" class="btn btn-outline-brown">
                <i class="bi bi-cart me-2"></i>Mi carrito
            </a>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }
    .badge {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
</style>
@endpush
