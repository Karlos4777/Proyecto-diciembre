@extends('web.app')
@section('contenido')

<!-- Sección Producto -->
<section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">

            <!-- Imagen del producto -->
            <div class="col-md-6">
                <img class="card-img-top mb-5 mb-md-0 producto-main-img"
                     src="{{ $producto->imagen ? asset('uploads/productos/' . $producto->imagen) : asset('img/no-image.jpg') }}" 
                     alt="{{ $producto->nombre }}" loading="lazy" decoding="async" />
            </div>

            <!-- Información del producto -->
            <div class="col-md-6">
                <div class="small mb-1">SKU: {{ $producto->codigo }}</div>
                <h1 class="product-title display-5 fw-bolder">{{ $producto->nombre }}</h1>

                <!-- Badges categoría y catálogo -->
                <p class="mb-2">
                    @if($producto->categoria)
                        <span class="badge bg-primary me-1">
                            <i class="bi bi-tags-fill me-1"></i>{{ $producto->categoria->nombre }}
                        </span>
                    @endif
                    @if($producto->catalogo)
                        <span class="badge bg-danger">
                            <i class="bi bi-bookmark-fill me-1"></i>{{ $producto->catalogo->nombre }}
                        </span>
                    @endif
                </p>

                <!-- Precio -->
                <div class="fs-5 mb-3">
                    @if($producto->tiene_descuento)
                        <span class="text-muted text-decoration-line-through me-2">${{ number_format($producto->precio, 2) }}</span>
                        <span class="product-price fw-bold">${{ number_format($producto->precio_con_descuento, 2) }}</span>
                        <small class="badge bg-warning text-dark ms-2">-{{ $producto->descuento }}%</small>
                    @else
                        <span class="product-price">${{ number_format($producto->precio, 2) }}</span>
                    @endif
                </div>

                <!-- Estado del stock -->
                @if ($producto->cantidad >= 21)
                    <p class="text-success fw-semibold mb-2">
                        <i class="bi bi-check-circle me-1"></i> Producto disponible
                    </p>
                @elseif ($producto->cantidad >= 1 && $producto->cantidad < 21)
                    <p class="text-warning fw-semibold mb-2">
                        <i class="bi bi-exclamation-circle me-1"></i> Pocas unidades
                    </p>
                @else
                    <p class="text-danger fw-semibold mb-2">
                        <i class="bi bi-x-circle me-1"></i> Producto no disponible
                    </p>
                @endif

                <!-- Descripción -->
                <p class="lead">{{ $producto->descripcion }}</p>

                <!-- Mensaje de éxito -->
                @if (session('mensaje'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('mensaje') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                <!-- Botones: agregar al carrito y regresar -->
                <div class="d-flex gap-2 mt-3">
                    @if ($producto->cantidad >= 1)
                        <form method="POST" action="{{ route('carrito.agregar') }}">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <button class="btn btn-product" type="submit">
                                <i class="bi-cart-fill me-1"></i> Agregar al carrito
                            </button>
                        </form>
                    @endif
                    <a class="btn btn-outline-secondary" href="javascript:history.back()">Regresar</a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
