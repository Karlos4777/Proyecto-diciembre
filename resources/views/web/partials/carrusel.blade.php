@if(empty($productos) || count($productos) === 0)
    <div class="text-center">
        <p class="text-muted">No se encontraron productos.</p>
    </div>
@else
<div class="position-relative carrusel-wrapper">
    <button class="carousel-btn left"><i class="bi bi-chevron-left"></i></button>

    <div class="productos-carrusel">
        @foreach ($productos as $producto)
            <div class="producto-card card shadow-sm">
                <div class="imagen-container">
                    <img src="{{ $producto->imagen ? asset('uploads/productos/' . $producto->imagen) : asset('img/no-image.jpg') }}"
                         alt="{{ $producto->nombre }}">
                </div>

                @php $stock = $producto->cantidad ?? 0; @endphp
                @if ($stock >= 50)
                    <span class="badge bg-success position-absolute top-0 start-0 m-2 p-2 rounded-3 shadow">
                        <i class="bi bi-check-circle me-1"></i> Disponible
                    </span>
                @elseif ($stock >= 1 && $stock < 50)
                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 p-2 rounded-3 shadow">
                        <i class="bi bi-exclamation-circle me-1"></i> Pocas unidades
                    </span>
                @else
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2 p-2 rounded-3 shadow">
                        <i class="bi bi-x-circle me-1"></i> Agotado
                    </span>
                @endif

                <div class="card-body text-center">
                    <h5 class="fw-bolder">{{ $producto->nombre }}</h5>
                    <p class="mb-1 text-muted small">
                        @if($producto->categoria)
                            <span class="badge bg-primary"><i class="bi bi-tags-fill me-1"></i>{{ $producto->categoria->nombre }}</span>
                        @endif
                        @if($producto->catalogo)
                            <span class="badge bg-danger ms-1"><i class="bi bi-bookmark-fill me-1"></i>{{ $producto->catalogo->nombre }}</span>
                        @endif
                    </p>
                    <p class="fw-bold text-success mb-2">${{ number_format($producto->precio, 2) }}</p>
                    <a href="{{ route('web.show', $producto->id) }}" class="btn btn-outline-dark btn-sm w-100">
                        <i class="bi bi-eye me-1"></i> Ver producto
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <button class="carousel-btn right"><i class="bi bi-chevron-right"></i></button>
</div>
@endif

<style>
.carrusel-wrapper {
    position: relative;
    overflow: hidden;
}
.carrusel-wrapper:hover .carousel-btn {
    opacity: 1;
    visibility: visible;
}
.productos-carrusel {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 1.5rem;
    padding: 20px;
}
.productos-carrusel::-webkit-scrollbar {
    height: 5px;
}
.productos-carrusel::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 4px;
}
.producto-card {
    flex: 0 0 260px;
    border-radius: 10px;
    transition: transform 0.3s ease;
    position: relative;
}
.producto-card:hover {
    transform: translateY(-5px);
}
.imagen-container {
    width: 100%;
    height: 250px;
    overflow: hidden;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    background-color: #f8f9fa;
}
.imagen-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: #212529;
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 2;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}
.carousel-btn:hover {
    background-color: #000;
}
.carousel-btn.left { left: 5px; }
.carousel-btn.right { right: 5px; }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".carrusel-wrapper").forEach(wrapper => {
        const carrusel = wrapper.querySelector(".productos-carrusel");
        const nextBtn = wrapper.querySelector(".carousel-btn.right");
        const prevBtn = wrapper.querySelector(".carousel-btn.left");

        nextBtn.addEventListener("click", () => carrusel.scrollBy({ left: 300, behavior: 'smooth' }));
        prevBtn.addEventListener("click", () => carrusel.scrollBy({ left: -300, behavior: 'smooth' }));
    });
});
</script>
