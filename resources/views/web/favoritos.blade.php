@extends('web.app')
@section('contenido')
<div class="container py-4">
    <h2 class="mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-heart-fill text-danger"></i> Mis Favoritos
    </h2>

    @if(session('mensaje'))
        <div class="alert alert-info">{{ session('mensaje') }}</div>
    @endif

    @if($items->isEmpty())
        <div class="alert alert-secondary">
            Aún no tienes productos en favoritos. Explora el catálogo y agrega lo que te guste.
        </div>
        <a href="{{ route('web.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Volver a la tienda
        </a>
    @else
        <div class="row g-3">
            @foreach($items as $fav)
                @php($producto = $fav->producto)
                @if($producto)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('web.partials.product-card-data', ['producto' => $producto, 'compact' => false])
                </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection