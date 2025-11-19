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
                    <div class="card-header bg-light">
                        <div class="row">
                            <div class="col-md-5"><strong>Producto</strong></div>
                            <div class="col-md-2 text-center"><strong>Precio</strong></div>
                            <div class="col-md-2 text-center"><strong>Cantidad</strong></div>
                            <div class="col-md-3 text-end"><strong>Subtotal</strong></div>
                        </div>
                    </div>
                    <div class="card-body" id="cartItems">
                        @forelse($carrito as $id => $item)
                        <div class="cart-item mb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-5 d-flex align-items-center">
                                    <img src="{{ asset('uploads/productos/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}" class="product-thumb me-3" style="width: 60px; height: 60px; object-fit: cover; flex-shrink: 0;" />
                                    <div>
                                        <div class="product-name fw-bold">{{ $item['nombre'] }}</div>
                                        <div class="product-code">{{ $item['codigo'] }}</div>
                                    </div>
                                </div>

                                <div class="col-md-2 text-center">
                                    @if(isset($item['precio_original']) && $item['precio_original'] > $item['precio'])
                                        <div>
                                            <small class="text-muted text-decoration-line-through">${{ number_format($item['precio_original'], 2) }}</small>
                                        </div>
                                    @endif
                                    <div class="product-price fw-bold">${{ number_format($item['precio'], 2) }}</div>
                                </div>

                                <div class="col-md-2 d-flex justify-content-center cart-qty">
                                    <div class="btn-group" role="group" aria-label="Cantidad">
                                        <a href="{{ route('carrito.restar', $id) }}" class="btn btn-outline-danger btn-sm">-</a>
                                        <span class="d-inline-flex align-items-center px-3">{{ $item['cantidad'] }}</span>
                                        <a href="{{ route('carrito.sumar', $id) }}" class="btn btn-outline-success btn-sm">+</a>
                                    </div>
                                </div>

                                <div class="col-md-3 text-end">
                                    <div class="fw-bold mb-2">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
                                    <a class="btn btn-sm btn-outline-danger" href="{{ route('carrito.eliminar', $id) }}">
                                        <i class="bi bi-trash"></i>
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
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Resumen del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong id="orderTotal">${{ number_format($total ?? 0, 2) }}</strong>
                        </div>
                        <!-- Checkout Button --><a href="{{ route('pedido.formulario') }}" class="btn btn-product w-100" id="checkout">
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
