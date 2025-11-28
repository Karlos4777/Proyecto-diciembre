@extends('web.app')
@section('contenido')

<!-- Sección Producto -->
<section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">

            <!-- Imagen del producto -->
            <div class="col-md-6">
                <img class="card-img-top mb-5 mb-md-0 producto-main-img"
                     src="{{ $producto->imagen ? asset('uploads/productos/' . $producto->imagen) : asset('img/no-image.jpg') }}" 
                     alt="{{ $producto->nombre }}" loading="lazy" decoding="async" />
            </div>

            <!-- Información del producto -->
            <div class="col-md-6">
                <div class="small mb-1">SKU: {{ $producto->codigo }}</div>
                <h1 class="product-title display-5 fw-bolder">{{ $producto->nombre }}</h1>

                <!-- Badges categoría y catálogo -->
                <p class="mb-2">
                    @if($producto->categoria)
                        <span class="badge bg-primary me-1">
                            <i class="bi bi-tags-fill me-1"></i>{{ $producto->categoria->nombre }}
                        </span>
                    @endif
                    @if($producto->catalogo)
                        <span class="badge bg-danger">
                            <i class="bi bi-bookmark-fill me-1"></i>{{ $producto->catalogo->nombre }}
                        </span>
                    @endif
                </p>

                <!-- Precio -->
                <div class="fs-5 mb-3">
                    @if($producto->tiene_descuento)
                        <span class="text-muted text-decoration-line-through me-2">${{ number_format($producto->precio, 2) }}</span>
                        <span class="product-price fw-bold">${{ number_format($producto->precio_con_descuento, 2) }}</span>
                        <small class="badge bg-warning text-dark ms-2">-{{ $producto->descuento }}%</small>
                    @else
                        <span class="product-price">${{ number_format($producto->precio, 2) }}</span>
                    @endif
                </div>

                <!-- Estado del stock -->
                @if ($producto->cantidad >= 21)
                    <p class="text-success fw-semibold mb-2">
                        <i class="bi bi-check-circle me-1"></i> Producto disponible
                    </p>
                @elseif ($producto->cantidad >= 1 && $producto->cantidad < 21)
                    <p class="text-warning fw-semibold mb-2">
                        <i class="bi bi-exclamation-circle me-1"></i> Pocas unidades
                    </p>
                @else
                    <p class="text-danger fw-semibold mb-2">
                        <i class="bi bi-x-circle me-1"></i> Producto no disponible
                    </p>
                @endif

                <!-- Descripción -->
                <p class="lead">{{ $producto->descripcion }}</p>

                <!-- Preview de Música (Spotify) -->
                @if($producto->preview_url)
                    <div class="card mb-3 border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-2">
                                <i class="bi bi-music-note-beamed text-success"></i> Preview de Audio
                            </h6>
                            @if($producto->artista)
                                <p class="mb-1 small text-muted">
                                    <strong>Artista:</strong> {{ $producto->artista }}
                                </p>
                            @endif
                            @if($producto->album)
                                <p class="mb-2 small text-muted">
                                    <strong>Álbum:</strong> {{ $producto->album }}
                                </p>
                            @endif
                            <audio controls class="w-100" style="max-width: 400px;">
                                <source src="{{ $producto->preview_url }}" type="audio/mpeg">
                                Tu navegador no soporta el elemento de audio.
                            </audio>
                        </div>
                    </div>
                @endif

                <!-- Mensaje de éxito -->
                @if (session('mensaje'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('mensaje') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                <!-- Botones: agregar al carrito, favoritos y regresar -->
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    @if ($producto->cantidad >= 1)
                        <form method="POST" action="{{ route('carrito.agregar') }}">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <button class="btn btn-outline-brown" type="submit">
                                <i class="bi-cart-fill me-1"></i> Agregar al carrito
                            </button>
                        </form>
                    @endif
                    
                    @auth
                        @php
                            $enFavoritos = \App\Models\Wishlist::where('user_id', auth()->id())
                                ->where('producto_id', $producto->id)
                                ->exists();
                        @endphp
                        <form method="POST" action="{{ route('favoritos.toggle', $producto->id) }}">
                            @csrf
                            <button type="submit" class="btn {{ $enFavoritos ? 'btn-danger' : 'btn-outline-danger' }}">
                                <i class="bi {{ $enFavoritos ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                                {{ $enFavoritos ? 'Quitar de Favoritos' : 'Agregar a Favoritos' }}
                            </button>
                        </form>
                    @endauth
                    
                    <a class="btn btn-outline-secondary" href="javascript:history.back()">Regresar</a>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Reseñas del producto -->
<section class="pb-5">
    <div class="container px-4 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header de reseñas -->
                <div class="card shadow-sm border-0 mb-4" style="background-color: #6F4E37;">
                    <div class="card-body text-white py-4">
                        <h3 class="mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-star-fill"></i> Reseñas del Producto
                        </h3>
                        @if($ratingPromedio)
                            <div class="d-flex align-items-center gap-3">
                                <span class="display-4 fw-bold">{{ number_format($ratingPromedio, 1) }}</span>
                                <div>
                                    <div class="text-warning mb-1" style="font-size: 1.5rem;">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="bi {{ $i <= round($ratingPromedio) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0 opacity-75">Basado en {{ $ratingCantidad }} {{ $ratingCantidad == 1 ? 'reseña' : 'reseñas' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="mb-0 opacity-75">
                                <i class="bi bi-info-circle me-2"></i>Aún no hay reseñas para este producto. ¡Sé el primero en opinar!
                            </p>
                        @endif
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('mensaje'))
                    <div class="alert alert-success">{{ session('mensaje') }}</div>
                @endif

                <!-- Formulario para nueva reseña -->
                @auth
                    @if($puedeResenar)
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header" style="background-color: #ffffff; border-bottom: 2px solid #6F4E37;">
                                <h5 class="mb-0" style="color: #6F4E37;">
                                    <i class="bi bi-pencil-square me-2"></i>Escribe tu reseña
                                </h5>
                            </div>
                            <div class="card-body" style="background-color: #ffffff;">
                                <form method="POST" action="{{ route('reviews.store', $producto->id) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="color: #6F4E37;">
                                            <i class="bi bi-star me-1"></i>Calificación
                                        </label>
                                        <div class="star-rating-container d-flex align-items-center gap-1 mb-2">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="bi bi-star star-input fs-3" data-value="{{ $i }}" style="cursor: pointer; color: #ccc; transition: all 0.2s;"></i>
                                            @endfor
                                            <span id="rating-text" class="ms-2 fw-semibold" style="color: #6F4E37;"></span>
                                        </div>
                                        <input type="hidden" name="rating" id="rating-input" required>
                                        <div id="rating-error" class="text-danger small" style="display:none;">
                                            <i class="bi bi-exclamation-circle me-1"></i>Por favor selecciona una calificación
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="color: #6F4E37;">
                                            <i class="bi bi-chat-left-text me-1"></i>Comentario (opcional)
                                        </label>
                                        <textarea name="comentario" class="form-control" rows="4" maxlength="1000" 
                                                  placeholder="Comparte tu experiencia con este producto..." 
                                                  style="border-color: #6F4E37;"></textarea>
                                        <small class="text-muted">Máximo 1000 caracteres</small>
                                    </div>
                                    <button type="submit" class="btn btn-outline-brown w-100">
                                        <i class="bi bi-send me-2"></i>Publicar Reseña
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif(auth()->check())
                        <div class="alert" style="background-color: #ffffff; border-color: #6F4E37; color: #6F4E37;">
                            <i class="bi bi-info-circle me-2"></i>Ya enviaste una reseña o necesitas comprar este producto primero.
                        </div>
                    @endif
                @endauth

                <!-- Lista de reseñas -->
                <div class="reviews-list">
                    @forelse($reviews as $rev)
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body" style="background-color: #ffffff;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 45px; height: 45px; background-color: #6F4E37; color: white; font-weight: bold;">
                                            {{ strtoupper(substr($rev->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong style="color: #6F4E37;">{{ $rev->user->name }}</strong>
                                            <div class="text-warning small">
                                                @for($i=1;$i<=5;$i++)
                                                    <i class="bi {{ $i <= $rev->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $rev->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @if($rev->comentario)
                                    <p class="mt-3 mb-2" style="color: #4A2F1E; line-height: 1.6;">
                                        <i class="bi bi-quote text-muted me-1"></i>{{ $rev->comentario }}
                                    </p>
                                @endif
                                @if(auth()->check() && (auth()->id() === $rev->user_id || auth()->user()->can('producto-list')))
                                    <form method="POST" action="{{ route('reviews.destroy', $rev->id) }}" class="mt-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('¿Seguro que deseas eliminar esta reseña?')">
                                            <i class="bi bi-trash me-1"></i> Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center py-5" style="background-color: #ffffff;">
                                <i class="bi bi-chat-left-dots" style="font-size: 3rem; color: #8B6F47;"></i>
                                <p class="text-muted mt-3 mb-0">No hay reseñas todavía. ¡Sé el primero en compartir tu opinión!</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-input');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');
    const ratingError = document.getElementById('rating-error');
    let currentRating = 0;

    const ratingLabels = {
        1: 'Malo',
        2: 'Regular',
        3: 'Bueno',
        4: 'Muy Bueno',
        5: 'Excelente'
    };

    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const value = this.getAttribute('data-value');
            highlightStars(value);
            ratingText.textContent = ratingLabels[value];
        });

        star.addEventListener('mouseout', function() {
            highlightStars(currentRating);
            ratingText.textContent = currentRating ? ratingLabels[currentRating] : '';
        });

        star.addEventListener('click', function() {
            currentRating = this.getAttribute('data-value');
            ratingInput.value = currentRating;
            highlightStars(currentRating);
            ratingError.style.display = 'none';
            
            // Animation effect
            this.style.transform = 'scale(1.2)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
    });

    function highlightStars(value) {
        stars.forEach(star => {
            const starValue = star.getAttribute('data-value');
            if (starValue <= value) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
                star.style.color = '#ffc107'; // Gold color
            } else {
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
                star.style.color = '#ccc';
            }
        });
    }
    
    // Form validation
    const form = ratingInput.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!ratingInput.value) {
                e.preventDefault();
                ratingError.style.display = 'block';
                // Shake animation for visual feedback
                const container = document.querySelector('.star-rating-container');
                container.style.transform = 'translateX(5px)';
                setTimeout(() => container.style.transform = 'translateX(-5px)', 100);
                setTimeout(() => container.style.transform = 'translateX(5px)', 200);
                setTimeout(() => container.style.transform = 'translateX(0)', 300);
            }
        });
    }
});
</script>
@endpush
