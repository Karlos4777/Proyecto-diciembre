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
