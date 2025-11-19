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
                    <div class="card shadow-sm border-0 @if(session('success') && $loop->first) border-success border-3 @endif">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    <i class="bi bi-file-text me-2"></i>Pedido #{{ $pedido->id }}
                                    @if(session('success') && $loop->first)
                                        <span class="badge bg-success ms-2">
                                            <i class="bi bi-star-fill me-1"></i>NUEVO
                                        </span>
                                    @endif
                                </h5>
                                <small class="text-muted">
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
                                <span class="badge {{ $colores[$pedido->estado] ?? 'bg-dark' }} fs-6">
                                    <i class="bi bi-circle-fill me-1"></i>{{ ucfirst($pedido->estado) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <h6 class="text-muted">Productos:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 40px;">Imagen</th>
                                                    <th>Producto</th>
                                                    <th style="width: 70px;">Cantidad</th>
                                                    <th style="width: 90px;">Precio Unit.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pedido->detalles as $detalle)
                                                    <tr>
                                                        <td>
                                                            @if($detalle->producto && $detalle->producto->imagen)
                                                                <img src="{{ asset('uploads/productos/' . $detalle->producto->imagen) }}" 
                                                                     class="img-thumbnail"
                                                                     style="width: 40px; height: 40px; object-fit: cover; padding: 2px !important;"
                                                                     alt="{{ $detalle->producto->nombre }}">
                                                            @else
                                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                                     style="width: 40px; height: 40px; flex-shrink: 0;">
                                                                    <i class="bi bi-image fs-6"></i>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <small><strong>{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</strong></small><br>
                                                            @if($detalle->producto && $detalle->producto->categoria)
                                                                <span class="badge bg-info fs-7">{{ $detalle->producto->categoria->nombre }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <small>{{ $detalle->cantidad }}</small>
                                                        </td>
                                                        <td class="text-end">
                                                            <small>${{ number_format($detalle->precio, 2) }}</small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Resumen del pedido</h6>
                                            <hr>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <span>${{ number_format($pedido->total, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Envío:</span>
                                                <span class="badge bg-success">Gratis</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <strong>Total:</strong>
                                                <strong class="text-success fs-5">${{ number_format($pedido->total, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light d-flex justify-content-between">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>{{ $pedido->created_at->format('d \d\e M \d\e Y') }}
                            </small>
                            @if($pedido->estado === 'pendiente')
                                <form action="{{ route('pedidos.cambiar.estado', $pedido->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="estado" value="cancelado">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('¿Estás seguro de cancelar este pedido?')">
                                        <i class="bi bi-trash me-1"></i> Cancelar
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
            <a href="{{ route('web.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver a la tienda
            </a>
            <a href="{{ route('carrito.mostrar') }}" class="btn btn-outline-primary">
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
