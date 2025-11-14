@extends('web.app')
@section('titulo', $categoria->nombre . ' - DisZone')
@section('header')
@endsection
@section('contenido')

<!-- Mensaje de éxito -->
@if (session('success'))
    <div class="alert alert-success text-center mt-3">
        {{ session('success') }}
    </div>
@endif

<!-- Título de la categoría -->
<div class="container mt-5">
    <h2 class="section-title text-start mb-4">{{ $categoria->nombre }}</h2>
</div>

<!-- Carrusel de productos de la categoría -->
@if($productos->count())
    <div class="container mt-3">
        <div class="productos-carrusel-wrapper">
            @include('web.partials.carrusel', ['productos' => $productos])
        </div>
    </div>
@else
    <div class="text-center">
        <p class="text-muted">No se encontraron productos en esta categoría.</p>
    </div>
@endif

<!-- Paginación -->
@if($productos->count())
    <div class="d-flex justify-content-center mt-3">
        {{ $productos->appends(request()->query())->links() }}
    </div>
@endif

@endsection
