@extends('web.app')
@section('titulo', $categoria->nombre . ' - DisZone')
@section('header')
@endsection
@section('contenido')
<!-- Formulario de búsqueda y filtro -->
<form method="GET" action="{{ route('categoria.show', $categoria->id) }}">
    <div class="container px-4 px-lg-5 mt-4">
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar productos..."
                        aria-label="Buscar productos" name="search" value="{{ request('search') }}">
                    <button class="btn btn-outline-dark" type="submit" id="searchButton">
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

<!-- Productos de la categoría -->

<div class="container mt-5">
    <h2 class="text-center mb-4">{{ $categoria->nombre }}</h2>

    @if($productos->count())
        <div class="row">
            @foreach ($productos as $producto)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($producto->imagen)
                            <img src="{{ asset('uploads/productos/' . $producto->imagen) }}" 
                                 class="card-img-top" alt="{{ $producto->nombre }}" 
                                 style="height: 250px; object-fit: cover;">
                        @else
                            <img src="{{ asset('img/no-image.jpg') }}" class="card-img-top" alt="Sin imagen">
                        @endif

                        <div class="card-body text-center">
                            <h5 class="fw-bolder">{{ $producto->nombre }}</h5>
                            <p class="mb-1 text-muted small">
                                {{ $categoria->nombre }}
                            </p>
                            <p class="fw-bold text-success mb-2">
                                ${{ number_format($producto->precio, 2) }}
                            </p>
                            <a href="{{ route('web.show', $producto->id) }}" class="btn btn-dark btn-sm">
                                Ver más
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $productos->appends(request()->query())->links() }}
        </div>
    @else
        <p class="text-center text-muted">No hay productos en esta categoría.</p>
    @endif
</div>
@endsection