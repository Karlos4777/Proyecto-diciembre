@extends('web.app')

@section('header')
@endsection

@section('contenido')

<!-- Mensaje de éxito -->
@if (session('success'))
    <div class="alert alert-success text-center mt-3">
        {{ session('success') }}
    </div>
@endif

<!-- Formulario de búsqueda -->
<form method="GET" action="{{ route('web.index') }}">
    <div class="container px-4 px-lg-5 mt-4">
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Buscar productos..." name="search"
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="input-group">
                    <label class="input-group-text" for="sortSelect">Ordenar por:</label>
                    <select class="form-select" id="sortSelect" name="sort" onchange="this.form.submit()">
                        <option value="">Seleccionar...</option>
                        <option value="priceAsc" {{ request('sort') == 'priceAsc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                        <option value="priceDesc" {{ request('sort') == 'priceDesc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>

<!--  SECCIÓN: LO MÁS RECIENTE -->
<div class="container mt-5">
    <h2 class="text-start mb-4">Lo más reciente</h2>
    <div class="productos-carrusel-wrapper">
        @include('web.partials.carrusel', ['productos' => $productosRecientes])
    </div>
</div>

<!--  SECCIONES: LO MÁS VENDIDO POR CATÁLOGO -->
@foreach ($productosVendidosPorCatalogo as $catalogo => $productos)
    <div class="container mt-5">
        <h2 class="text-start mb-4">Lo más vendido en {{ $catalogo }}</h2>
        <div class="productos-carrusel-wrapper">
            @include('web.partials.carrusel', ['productos' => $productos])
        </div>
    </div>
@endforeach

<!--  SECCIÓN: VISTO RECIENTEMENTE -->
@if (!empty($vistosRecientemente))
    <div class="container mt-5 mb-5">
        <h2 class="text-start mb-4">Visto recientemente</h2>
        <div class="productos-carrusel-wrapper">
            @include('web.partials.carrusel', ['productos' => $vistosRecientemente])
        </div>
    </div>
@endif

@endsection
