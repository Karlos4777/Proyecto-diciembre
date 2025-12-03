@extends('web.app')
@section('contenido')
<!-- Section-->
<section class="py-5">
    <div class="container px-4 px-lg-12 my-5">
        <h2 class="section-title fw-bold mb-4">Detalle de su Pedido</h2>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #6F4E37; color: white;">
                        <div class="row">
                            <div class="col-md-2 text-center"><strong>Imagen</strong></div>
                            <div class="col-md-2"><strong>Producto</strong></div>
                            <div class="col-md-1 text-center"><strong>Código</strong></div>
                            <div class="col-md-2 text-center"><strong>Categoría</strong></div>
                            <div class="col-md-1 text-center"><strong>Precio</strong></div>
                            <div class="col-md-2 text-center"><strong>Cantidad</strong></div>
                            <div class="col-md-2 text-end"><strong>Subtotal</strong></div>
                        </div>
                    </div>
                    <div class="card-body" id="cartItems">
                        @forelse($carrito as $id => $item)
                        <div class="cart-item mb-3 border-bottom pb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-2 text-center">
                                    <img src="{{ asset('uploads/productos/' . $item['imagen']) }}" 
                                         alt="{{ $item['nombre'] }}" 
                                         class="rounded shadow-sm" 
                                         style="width: 80px; height: 80px; object-fit: cover;" />
                                </div>

                                <div class="col-md-2">
                                    <div class="product-name fw-bold" style="color: #4A2F1E;">{{ $item['nombre'] }}</div>
                                </div>

                                <div class="col-md-1 text-center">
                                    <span class="badge bg-secondary">{{ $item['codigo'] ?? '-' }}</span>
                                </div>

                                <div class="col-md-2 text-center">
                                    <span class="badge" style="background-color: #efe3d8; color: #6F4E37;">
                                        {{ $item['categoria'] ?? 'Sin categoría' }}
                                    </span>
                                </div>

                                <div class="col-md-1 text-center">
                                    @if(isset($item['precio_original']) && $item['precio_original'] > $item['precio'])
                                        <div>
                                            <small class="text-muted text-decoration-line-through">${{ number_format($item['precio_original'], 0, ',', '.') }}</small>
                                        </div>
                                    @endif
                                    <div class="product-price fw-bold" style="color: #6F4E37;">${{ number_format($item['precio'], 0, ',', '.') }}</div>
                                </div>

                                <div class="col-md-2 d-flex justify-content-center cart-qty">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Cantidad">
                                        <a href="{{ route('carrito.restar', $id) }}" class="btn btn-outline-danger">-</a>
                                        <span class="d-inline-flex align-items-center px-3 border-top border-bottom">{{ $item['cantidad'] }}</span>
                                        <a href="{{ route('carrito.sumar', $id) }}" class="btn btn-outline-success">+</a>
                                    </div>
                                </div>

                                <div class="col-md-2 text-end">
                                    <div class="fw-bold mb-2" style="color: #6F4E37;">${{ number_format($item['precio'] * $item['cantidad'], 0, ',', '.') }}</div>
                                    <a class="btn btn-sm btn-outline-danger" href="{{ route('carrito.eliminar', $id) }}">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="cart-empty">
                            <p>Tu carrito está vacío</p>
                            <a href="/" class="btn btn-outline-primary mt-2">Continuar comprando</a>
                        </div>
                        @endforelse
                    </div>
                    @if (session('mensaje'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('mensaje') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col text-end">
                                <a class="btn btn-outline-danger me-2" href="{{route('carrito.vaciar')}}">
                                    <i class="bi bi-x-circle me-1"></i>Vaciar carrito
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <!-- Puntos Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-music-note-beamed me-2"></i>Puntos Musicales</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">Tienes <strong class="text-primary">{{ auth()->user()->puntos }}</strong> puntos disponibles.</p>
                        <small class="text-muted d-block mb-3">Canjea 1 punto por $100 de descuento.</small>
                        
                        @if(isset($puntosCanjeados) && $puntosCanjeados > 0)
                            <div class="alert alert-success py-2 mb-0 d-flex justify-content-between align-items-center">
                                <small><i class="bi bi-check-circle me-1"></i>Canjeados: {{ $puntosCanjeados }} pts</small>
                                <a href="{{ route('carrito.quitar.puntos') }}" class="btn btn-sm btn-link text-danger p-0" title="Quitar descuento">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            </div>
                        @else
                            <form action="{{ route('carrito.canjear.puntos') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="number" name="puntos" class="form-control form-control-sm" placeholder="Cant. puntos" min="1" step="1" max="{{ auth()->user()->puntos }}">
                                <button type="submit" class="btn btn-sm btn-brown text-white" style="background-color: #6F4E37;">Canjear</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Resumen del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong>${{ number_format($total ?? 0, 0, ',', '.') }}</strong>
                        </div>

                        @if(isset($descuentoPuntos) && $descuentoPuntos > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span><i class="bi bi-stars me-1"></i>Descuento Puntos</span>
                            <strong>-${{ number_format($descuentoPuntos, 0, ',', '.') }}</strong>
                        </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong id="orderTotal" class="fs-4" style="color: #6F4E37;">${{ number_format($totalConDescuento ?? $total, 0, ',', '.') }}</strong>
                        </div>
                        <!-- Checkout Button --><a href="{{ route('pedido.formulario') }}" class="btn btn-outline-brown w-100" id="checkout">
                        <i class="bi bi-credit-card me-1"></i>Realizar pedido
                        </a>
                         
                        <!-- Continue Shopping -->
                        <a href="/" class="btn btn-outline-secondary w-100 mt-3">
                            <i class="bi bi-arrow-left me-1"></i>Continuar comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
