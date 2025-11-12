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
