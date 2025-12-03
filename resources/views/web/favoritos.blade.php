@extends('web.app')
@section('contenido')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3" style="border-color: var(--color-accent) !important;">
        <h2 class="section-title m-0">
            <i class="bi bi-heart text-danger me-2"></i> Mis Favoritos
        </h2>
        <span class="badge rounded-pill fs-6" style="background-color: var(--color-accent);">{{ $items->count() }} productos</span>
    </div>

    @if(session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 5px solid var(--color-accent);">
            <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($items->isEmpty())
        <div class="text-center py-5" style="background-color: var(--color-cream); border-radius: 16px;">
            <div class="mb-4">
                <i class="bi bi-heart text-muted" style="font-size: 5rem; opacity: 0.5;"></i>
            </div>
            <h3 class="fw-normal mb-3" style="color: var(--color-dark-brown); font-family: var(--font-main);">Tu lista de deseos está vacía</h3>
            <p class="text-muted mb-4">Guarda los productos que más te gusten para no perderlos de vista.</p>
            <a href="{{ route('web.index') }}" class="btn btn-theme btn-lg px-5 rounded-pill shadow-sm">
                <i class="bi bi-cart-plus me-2"></i> Explorar Catálogo
            </a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($items as $fav)
                @php($producto = $fav->producto)
                @if($producto)
                <div class="col">
                    @include('web.partials.product-card-data', ['producto' => $producto, 'compact' => false])
                </div>
                @endif
            @endforeach
        </div>
        
        <div class="mt-5 text-center">
            <a href="{{ route('web.index') }}" class="btn btn-theme rounded-pill">
                <i class="bi bi-arrow-left me-2"></i> Seguir comprando
            </a>
        </div>
    @endif
</div>
@endsection