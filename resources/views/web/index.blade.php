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

<!-- ✅ Otros mensajes de error -->
@if (session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill me-3 fs-4 text-danger"></i>
                <div>
                    <h5 class="alert-heading mb-1">Error</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<!-- 🔍 Formulario de búsqueda con autocompletado -->
<div class="container px-4 px-lg-5 mt-4">
    <div class="row">
        <div class="col-md-8 mb-3 position-relative">
            <form id="formBuscador" method="GET" action="{{ route('web.index') }}">
                <div class="input-group">
                    <input type="text" id="buscador" class="form-control" placeholder="Buscar productos..." name="search" autocomplete="off" value="{{ request('search') }}">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>

            <!-- 📋 Contenedor para los resultados -->
            <ul id="resultadosBusqueda" class="list-group position-absolute w-100 mt-1"></ul>
        </div>
    </div>
</div>

<!-- 🆕 SECCIÓN: LO MÁS RECIENTE -->
<div class="container mt-5">
    <h2 class="text-start mb-4">Lo más reciente</h2>
    <div class="productos-carrusel-wrapper">
        @include('web.partials.carrusel', ['productos' => $productosRecientes])
    </div>
</div>

<!-- 💥 SECCIONES: LO MÁS VENDIDO POR CATÁLOGO -->
@foreach ($productosVendidosPorCatalogo as $catalogo => $productos)
    <div class="container mt-5">
        <h2 class="text-start mb-4">Lo más vendido en {{ $catalogo }}</h2>
        <div class="productos-carrusel-wrapper">
            @include('web.partials.carrusel', ['productos' => $productos])
        </div>
    </div>
@endforeach

<!-- 👀 SECCIÓN: VISTO RECIENTEMENTE -->
@if (!empty($vistosRecientemente))
    <div class="container mt-5 mb-5">
        <h2 class="text-start mb-4">Visto recientemente</h2>
        <div class="productos-carrusel-wrapper">
            @include('web.partials.carrusel', ['productos' => $vistosRecientemente])
        </div>
    </div>
@endif

@endsection

@push('styles')
<!-- 🎨 Estilos del buscador -->
<style>
#resultadosBusqueda {
    display: none;
    z-index: 1000;
    max-height: 320px;
    overflow-y: auto;
    border-radius: 0 0 6px 6px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

#resultadosBusqueda .list-group-item {
    transition: background-color 0.2s ease;
}

#resultadosBusqueda .list-group-item:hover {
    background-color: #f8f9fa;
}

#resultadosBusqueda a {
    display: block;
    text-decoration: none;
    color: #212529;
}

#resultadosBusqueda small {
    font-size: 0.85rem;
}
</style>
@endpush

@push('scripts')
<!-- ⚙️ Script de búsqueda AJAX -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('buscador');
    const resultados = document.getElementById('resultadosBusqueda');
    const form = document.getElementById('formBuscador');

    // 🔍 Autocompletado en tiempo real
    input.addEventListener('keyup', async (e) => {
        const query = input.value.trim();

        // Si presiona Enter → búsqueda normal
        if (e.key === 'Enter') {
            form.submit();
            return;
        }

        // Si hay menos de 2 letras, ocultar
        if (query.length < 2) {
            resultados.style.display = 'none';
            resultados.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`/buscar-productos?search=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.length === 0) {
                resultados.innerHTML = '<li class="list-group-item text-muted">No se encontraron productos</li>';
            } else {
                resultados.innerHTML = data.map(p => `
                    <li class="list-group-item">
                        <a href="/producto/${p.id}" class="d-block">
                            <strong>${p.nombre}</strong> - $${parseFloat(p.precio).toFixed(2)} <br>
                            <small class="text-muted">${p.categoria} | ${p.catalogo}</small><br>
                            <span class="badge ${
                                p.estado === 'Disponible' ? 'bg-success' :
                                p.estado === 'Pocas unidades' ? 'bg-warning text-dark' :
                                'bg-danger'
                            }">${p.estado}</span>
                        </a>
                    </li>
                `).join('');
            }

            resultados.style.display = 'block';
        } catch (error) {
            console.error('Error en la búsqueda:', error);
        }
    });

    // 🧹 Ocultar resultados al hacer click fuera
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#buscador') && !e.target.closest('#resultadosBusqueda')) {
            resultados.style.display = 'none';
        }
    });
});

// ✅ Auto-cerrar mensaje de éxito después de 5 segundos
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