@extends('web.app')

@section('header')
@endsection

@section('contenido')

<!-- ✅ Mensaje de éxito (Compra realizada) -->
@if (session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-start">
                <i class="bi bi-check-circle-fill me-3 fs-4 text-success flex-shrink-0 mt-1"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">¡Compra exitosa!</h5>
                    <p class="mb-2">{{ session('success') }}</p>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('perfil.pedidos') }}" class="btn btn-success">
                            <i class="bi bi-bag-check me-1"></i> Ver mis pedidos
                        </a>
                        <a href="{{ route('web.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-shop me-1"></i> Continuar comprando
                        </a>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<!--  SECCIÓN: LO MÁS RECIENTE -->
<div class="container mt-5">
    <h2 class="section-title text-start mb-4">Lo más reciente</h2>
    <div class="productos-carrusel-wrapper">
        @include('web.partials.carrusel', ['productos' => $productosRecientes])
    </div>
</div>

<!-- SECCIONES: LO MÁS VENDIDO POR CATÁLOGO -->
@foreach ($productosVendidosPorCatalogo as $catalogo => $productos)
    <div class="container mt-5">
        <h2 class="section-title text-start mb-4">Lo más vendido en {{ $catalogo }}</h2>
        <div class="productos-carrusel-wrapper">
            @include('web.partials.carrusel', ['productos' => $productos])
        </div>
    </div>
@endforeach

<!-- 👀 SECCIÓN: VISTO RECIENTEMENTE -->
@if (!empty($vistosRecientemente))
    <div class="container mt-5 mb-5">
        <h2 class="section-title text-start mb-4">Visto recientemente</h2>
        <div class="productos-carrusel-wrapper">
            @include('web.partials.carrusel', ['productos' => $vistosRecientemente])
        </div>
    </div>
@endif

@endsection

@push('styles')
<!-- Estilos específicos de la página (sin repetir estilos del buscador, que están en el partial nav) -->
@endpush

@push('scripts')
<!-- ✅ Mantener solo el script de comportamiento específico de la página (no duplicar la búsqueda) -->
<script>
// Auto-cerrar mensaje de éxito después de 5 segundos
document.addEventListener('DOMContentLoaded', () => {
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            const alert = new bootstrap.Alert(successAlert);
            alert.close();
        }, 5000); // 5 segundos
    }
});
</script>
@endpush