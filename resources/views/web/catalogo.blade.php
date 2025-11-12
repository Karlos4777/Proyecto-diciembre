@extends('web.app')
@section('titulo', $catalogo->nombre . ' - DisZone')
@section('header')
@endsection
@section('contenido')

<!-- Mensaje de éxito -->
@if (session('success'))
    <div class="alert alert-success text-center mt-3">
        {{ session('success') }}
    </div>
@endif

<!-- Formulario de búsqueda y filtro -->
<form method="GET" action="{{ route('catalogo.show', $catalogo->id) }}">
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

<!-- Título del catálogo -->
<div class="container mt-5">
    <h2 class="text-start mb-4">{{ $catalogo->nombre }}</h2>
</div>

<!-- Carrusel de productos del catálogo -->
@if($productos->count())
    <div class="container mt-3">
        <div class="productos-carrusel-wrapper">
            @include('web.partials.carrusel', ['productos' => $productos])
        </div>
    </div>
@else
    <div class="text-center">
        <p class="text-muted">No se encontraron productos en este catálogo.</p>
    </div>
@endif

<!-- Paginación -->
@if($productos->count())
    <div class="d-flex justify-content-center mt-3">
        {{ $productos->appends(request()->query())->links() }}
    </div>
@endif

@endsection
