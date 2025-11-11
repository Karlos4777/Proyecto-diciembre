@extends('web.app')
@section('contenido')
<!-- Section-->
<form action="{{ route('carrito.agregar') }}" method="POST" class="d-flex">
    @csrf
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <!-- Imagen del producto -->
                <div class="col-md-6">
                    <img class="card-img-top mb-5 mb-md-0"
                         src="{{ asset('uploads/productos/' . $producto->imagen) }}" 
                         alt="{{ $producto->nombre }}" />
                </div>

                <!-- Información del producto -->
                <div class="col-md-6">
                    <div class="small mb-1">SKU: {{ $producto->codigo }}</div>
                    <h1 class="display-5 fw-bolder">{{ $producto->nombre }}</h1>

                
                    <!-- Badges categoría y catálogo con iconos y colores -->
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

                    <div class="fs-5 mb-5">
                        <span>${{ number_format($producto->precio, 2) }}</span>
                    </div>
                    
                    @if ($producto->cantidad >= 50)
                <p class="text-success fw-semibold mb-2">
                    <i class="bi bi-check-circle me-1"></i> Producto disponible
                </p>
            @elseif ($producto->cantidad >= 1 && $producto->cantidad < 50)
                <p class="text-warning fw-semibold mb-2">
                    <i class="bi bi-exclamation-circle me-1"></i> Pocas unidades
                </p>
            @elseif ($producto->cantidad == 0)
                <p class="text-danger fw-semibold mb-2">
                    <i class="bi bi-x-circle me-1"></i> Producto no disponible
                </p>
            @endif

                    <p class="lead">{{ $producto->descripcion }}</p>

                    @if (session('mensaje'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('mensaje') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    <!-- Formulario cantidad + carrito -->
                    @if ($producto->cantidad >= 1)
                   <form method="POST" action="{{ route('carrito.agregar') }}" class="d-flex">
    @csrf
    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
    <button class="btn btn-outline-dark flex-shrink-0" type="submit">
        <i class="bi-cart-fill me-1"></i> Agregar al carrito
    </button>
    <a class="btn btn-outline-dark flex-shrink-0" href="javascript:history.back()">Regresar</a>
</form>
                        @else
                        <a class="btn btn-outline-dark flex-shrink-0" href="javascript:history.back()">Regresar</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
@endsection
