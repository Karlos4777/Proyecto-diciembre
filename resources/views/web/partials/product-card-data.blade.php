{{-- 
    Componente reutilizable para mostrar información de productos
    Variables requeridas: $producto
    Variables opcionales: $compact (true para vista compacta en búsqueda)
--}}

@php
    $stock = $producto->cantidad ?? 0;
    $compact = $compact ?? false;
    
    // Determinar estado del stock
    if ($stock >= 21) {
        $estadoClass = 'bg-success';
        $estadoIcon = 'bi-check-circle';
        $estadoText = 'Disponible';
    } elseif ($stock >= 1 && $stock < 21) {
        $estadoClass = 'bg-warning text-dark';
        $estadoIcon = 'bi-exclamation-circle';
        $estadoText = 'Pocas unidades';
    } else {
        $estadoClass = 'bg-danger';
        $estadoIcon = 'bi-x-circle';
        $estadoText = 'Agotado';
    }
    
    // Calcular precio con descuento
    $descuento = $producto->descuento ?? 0;
    $precio = $producto->precio ?? 0;
    $precioConDescuento = isset($producto->precio_con_descuento) 
        ? $producto->precio_con_descuento 
        : ($descuento > 0 ? $precio * (1 - $descuento / 100) : $precio);
    
    // Determinar ruta de imagen
    $imagenUrl = '/img/sin-imagen.png';
    if (!empty($producto->imagen)) {
        if (str_starts_with($producto->imagen, 'http')) {
            $imagenUrl = $producto->imagen;
        } else {
            $imagenUrl = asset('uploads/productos/' . $producto->imagen);
        }
    }
@endphp

<div class="product-card-unified producto-card card shadow-sm {{ $compact ? 'compact' : 'full' }}" role="listitem" data-product-id="{{ $producto->id }}">
    
    {{-- Imagen del producto --}}
    <div class="product-image-wrapper imagen-container {{ $compact ? 'compact-image' : '' }}">
        <img 
            loading="lazy" 
            decoding="async"
            src="{{ $imagenUrl }}" 
            alt="{{ $producto->nombre ?? 'Producto' }}" 
            class="product-image d-block w-100"
        >
        
        {{-- Badge de estado (stock) --}}
        <span class="badge {{ $estadoClass }} position-absolute top-0 start-0 m-2 p-2 rounded-3 shadow product-badge">
            <i class="bi {{ $estadoIcon }} me-1"></i>
            @if(!$compact)
                {{ $estadoText }}
            @endif
        </span>
        
        {{-- Badge de descuento --}}
        @if($descuento > 0)
            <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2 p-2 rounded-3 shadow product-badge">
                -{{ $descuento }}%
            </span>
        @endif
    </div>
    
    {{-- Contenido del producto --}}
    <div class="product-content card-body text-center {{ $compact ? 'compact-content' : '' }}">
        
        {{-- Nombre del producto --}}
        <h5 class="fw-bolder product-title product-name {{ $compact ? 'compact-title' : '' }}">
            {{ Str::limit($producto->nombre ?? 'Sin nombre', $compact ? 45 : 60) }}
        </h5>
        
        {{-- Badges de categoría y catálogo --}}
        <p class="product-badges mb-1 text-muted small">
            @if(isset($producto->categoria) && $producto->categoria)
                <span class="badge bg-primary product-category-badge">
                    <i class="bi bi-tags-fill me-1"></i>
                    {{ is_object($producto->categoria) ? $producto->categoria->nombre : $producto->categoria }}
                </span>
            @endif
            
            @if(isset($producto->catalogo) && $producto->catalogo)
                <span class="badge bg-danger ms-1 product-catalog-badge">
                    <i class="bi bi-bookmark-fill me-1"></i>
                    {{ is_object($producto->catalogo) ? $producto->catalogo->nombre : $producto->catalogo }}
                </span>
            @endif
        </p>
        
        {{-- Precio --}}
        <p class="product-price-wrapper fw-bold text-success mb-2 price-final">
            ${{ number_format($precioConDescuento, 2) }}
        </p>

        {{-- Rating promedio --}}
        @php
            $ratingProm = $producto->rating_promedio ?? null;
        @endphp
        @if($ratingProm)
            <div class="mb-2 small text-warning">
                @for($i=1;$i<=5;$i++)
                    <i class="bi {{ $i <= round($ratingProm) ? 'bi-star-fill' : 'bi-star' }}"></i>
                @endfor
                <span class="text-muted ms-1">{{ $ratingProm }} ({{ $producto->rating_cantidad }})</span>
            </div>
        @endif
        
        {{-- Botón (solo en vista full) --}}
        @if(!$compact)
            <a href="{{ route('web.show', $producto->id) }}" class="btn btn-product btn-sm w-100">
                <i class="bi bi-eye me-1"></i> Ver producto
            </a>
            @auth
                @php
                    $enFavoritos = \App\Models\Wishlist::where('user_id', auth()->id())
                        ->where('producto_id', $producto->id)
                        ->exists();
                @endphp
                <form method="POST" action="{{ route('favoritos.toggle', $producto->id) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-sm w-100 {{ $enFavoritos ? 'btn-outline-danger' : 'btn-outline-secondary' }}">
                        <i class="bi {{ $enFavoritos ? 'bi-heart-fill text-danger' : 'bi-heart' }} me-1"></i>
                        {{ $enFavoritos ? 'Quitar de Favoritos' : 'Agregar a Favoritos' }}
                    </button>
                </form>
            @endauth
        @endif
    </div>
</div>
