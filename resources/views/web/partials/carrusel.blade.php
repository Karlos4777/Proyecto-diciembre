@if(empty($productos) || count($productos) === 0)
    <div class="text-center">
        <p class="text-muted">No se encontraron productos.</p>
    </div>
@else
<div class="position-relative carrusel-wrapper" role="region" aria-label="Productos destacados">
    <button class="carousel-btn left" aria-label="Anterior" title="Anterior"><i class="bi bi-chevron-left"></i></button>
    <div class="productos-carrusel" role="list">

        @foreach ($productos as $producto)
            @include('web.partials.product-card-data', ['producto' => $producto, 'compact' => false])
        @endforeach

    </div>

    <button class="carousel-btn right" aria-label="Siguiente" title="Siguiente"><i class="bi bi-chevron-right"></i></button>
</div>
@endif
