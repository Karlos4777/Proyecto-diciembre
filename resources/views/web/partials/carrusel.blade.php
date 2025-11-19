@if(empty($productos) || count($productos) === 0)
    <div class="text-center">
        <p class="text-muted">No se encontraron productos.</p>
    </div>
@else
<div class="position-relative carrusel-wrapper" role="region" aria-label="Productos destacados">
    <button class="carousel-btn left" aria-label="Anterior" title="Anterior"><i class="bi bi-chevron-left"></i></button>
    <div class="productos-carrusel" role="list">

        @foreach ($productos as $producto)
            <div class="producto-card card shadow-sm" role="listitem" data-product-id="{{ $producto->id }}">
                <div class="imagen-container">
                    <img loading="lazy" decoding="async" src="{{ $producto->imagen ? asset('uploads/productos/' . $producto->imagen) : asset('img/no-image.jpg') }}"
                        alt="{{ $producto->nombre }}" class="d-block w-100" />
                </div>

                @php $stock = $producto->cantidad ?? 0; @endphp

                @if ($stock >= 21)
                    <span class="badge bg-success position-absolute top-0 start-0 m-2 p-2 rounded-3 shadow">
                        <i class="bi bi-check-circle me-1"></i> Disponible
                    </span>
                @elseif ($stock >= 1 && $stock < 21)
                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 p-2 rounded-3 shadow">
                        <i class="bi bi-exclamation-circle me-1"></i> Pocas unidades
                    </span>
                @else
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2 p-2 rounded-3 shadow">
                        <i class="bi bi-x-circle me-1"></i> Agotado
                    </span>
                @endif

                @if (!empty($producto->descuento) && $producto->descuento > 0)
                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2 p-2 rounded-3 shadow">
                        -{{ $producto->descuento }}%
                    </span>
                @endif

                <div class="card-body text-center">
                    <h5 class="fw-bolder product-name">{{ $producto->nombre }}</h5>
                    <p class="mb-1 text-muted small">
                        @if($producto->categoria)
                            <span class="badge bg-primary"><i class="bi bi-tags-fill me-1"></i>{{ $producto->categoria->nombre }}</span>
                        @endif
                        @if($producto->catalogo)
                            <span class="badge bg-danger ms-1"><i class="bi bi-bookmark-fill me-1"></i>{{ $producto->catalogo->nombre }}</span>
                        @endif
                    </p>

                    @if(!empty($producto->descuento) && $producto->descuento > 0)
                        <p class="fw-bold mb-2">
                            <small class="text-muted text-decoration-line-through me-2">${{ number_format($producto->precio, 2) }}</small>
                            <span class="text-success">${{ number_format($producto->precio_con_descuento, 2) }}</span>
                        </p>
                    @else
                        <p class="fw-bold text-success mb-2">${{ number_format($producto->precio, 2) }}</p>
                    @endif

                    <a href="{{ route('web.show', $producto->id) }}" class="btn btn-product btn-sm w-100">
                        <i class="bi bi-eye me-1"></i> Ver producto
                    </a>
                </div>
            </div>
        @endforeach

    </div>

    <button class="carousel-btn right"><i class="bi bi-chevron-right"></i></button>
</div>
@endif
