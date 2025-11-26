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

                <!-- Botones: agregar al carrito y regresar -->
                <div class="d-flex gap-2 mt-3">
                    @if ($producto->cantidad >= 1)
                        <form method="POST" action="{{ route('carrito.agregar') }}">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <button class="btn btn-product" type="submit">
                                <i class="bi-cart-fill me-1"></i> Agregar al carrito
                            </button>
                        </form>
                    @endif
                    <a class="btn btn-outline-secondary" href="javascript:history.back()">Regresar</a>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Reseñas del producto -->
<section class="pb-5">
    <div class="container px-4 px-lg-5">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-star-fill text-warning"></i> Reseñas
                </h3>
                @if($ratingPromedio)
                    <p class="mb-2">
                        <strong class="fs-5">{{ $ratingPromedio }}</strong>
                        <span class="text-warning">
                            @for($i=1;$i<=5;$i++)
                                <i class="bi {{ $i <= round($ratingPromedio) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </span>
                        <span class="text-muted">({{ $ratingCantidad }} reseñas)</span>
                    </p>
                @else
                    <p class="text-muted">Aún no hay reseñas para este producto.</p>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('mensaje'))
                    <div class="alert alert-success">{{ session('mensaje') }}</div>
                @endif

                <!-- Formulario para nueva reseña -->
                @auth
                    @if($puedeResenar)
                        <form method="POST" action="{{ route('reviews.store', $producto->id) }}" class="mb-4 p-3 border rounded bg-light">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Calificación (1 a 5)</label>
                                <select name="rating" class="form-select" required>
                                    <option value="">Selecciona...</option>
                                    @for($i=1;$i<=5;$i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i==1 ? 'estrella' : 'estrellas' }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Comentario (opcional)</label>
                                <textarea name="comentario" class="form-control" rows="3" maxlength="1000" placeholder="¿Qué te pareció el producto?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i> Enviar reseña
                            </button>
                        </form>
                    @elseif(auth()->check())
                        <div class="alert alert-secondary">Ya enviaste una reseña o no has comprado este producto.</div>
                    @endif
                @endauth

                <!-- Lista de reseñas -->
                <div class="list-group">
                    @forelse($reviews as $rev)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $rev->user->name }}</strong>
                                    <div class="text-warning small">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="bi {{ $i <= $rev->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                        <span class="ms-1 text-muted">{{ $rev->rating }}/5</span>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $rev->created_at->diffForHumans() }}</small>
                            </div>
                            @if($rev->comentario)
                                <p class="mt-2 mb-1">{{ $rev->comentario }}</p>
                            @endif
                            @if(auth()->check() && (auth()->id() === $rev->user_id || auth()->user()->can('producto-list')))
                                <form method="POST" action="{{ route('reviews.destroy', $rev->id) }}" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="list-group-item text-muted">No hay reseñas todavía.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
