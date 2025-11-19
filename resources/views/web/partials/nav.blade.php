<nav class="app-header navbar navbar-expand-lg app-navbar">
    <div class="container-fluid px-4 px-lg-5">

        <!-- Marca -->
        <a class="navbar-brand fw-bold text-uppercase" href="{{ url('/') }}">
            <img src="{{ asset('assets/img/nav-logo-img.png') }}" alt="DiscZone" class="nav-logo d-inline-block align-middle">
        </a>

        <!-- Botón responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido colapsable -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            <!-- 🔍 Buscador principal -->
            <form id="formBuscador" class="d-flex mx-auto position-relative w-lg-50 search-form" method="GET" action="{{ route('web.index') }}" style="z-index: 1000;">
                <div class="search-wrapper position-relative w-100">
                    <i class="bi bi-search search-icon d-lg-none"></i>
                    <input type="text" id="buscador" class="form-control" placeholder="Buscar productos..." name="search" autocomplete="off" value="{{ request('search') }}">
                </div>
               <ul id="resultadosBusqueda" class="list-group position-absolute w-100">
                </ul>
            </form>

            <!-- Enlaces principales -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center text-lg-start">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('web') ? 'active' : '' }}" href="{{ route('web.index') }}">Inicio</a>
                </li>

                <!-- Dropdown Catálogos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownCatalogo" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">Catálogos</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownCatalogo">
                        @forelse($catalogos as $catalogo)
                            <li>
                                <a class="dropdown-item" href="{{ route('catalogo.show', $catalogo->id) }}">
                                    {{ $catalogo->nombre }}
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-muted">Sin catálogos</span></li>
                        @endforelse
                    </ul>
                </li>

                <!-- Dropdown Categorías -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownCategoria" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">Categorías</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownCategoria">
                        @forelse($categorias as $categoria)
                            <li>
                                <a class="dropdown-item" href="{{ route('categoria.show', $categoria->id) }}">
                                    {{ $categoria->nombre }}
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-muted">Sin categorías</span></li>
                        @endforelse
                    </ul>
                </li>

                <!-- Carrito -->
                @auth
                <li class="nav-item mb-2 mb-lg-0 me-3 me-lg-4">
                    <a href="{{ route('carrito.mostrar') }}" class="btn btn-carrito d-flex align-items-center justify-content-center position-relative w-100 w-lg-auto">
                        <i class="bi bi-cart-fill me-2 me-lg-2 me-0"></i>
                        <span class="d-none d-lg-inline">Pedido</span>
                        @php
                            $registro = \App\Models\Carrito::where('user_id', auth()->id())->first();
                            $cantidad = $registro ? array_sum(array_column($registro->contenido ?? [], 'cantidad')) : 0;
                        @endphp
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark text-white">
                            {{ $cantidad }}
                        </span>
                    </a>
                </li>
                @endauth
                 <!-- Sistema (junto al carrito) -->
                @canany(['user-list', 'rol-list', 'producto-list', 'catalogo-list', 'categoria-list'])
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center" id="navbarDropdownSistema" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear-fill me-1 me-lg-1 me-0"></i>
                        <span class="d-none d-lg-inline">Sistema</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdownSistema">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        @can('user-list')
                            <li>
                                <a class="dropdown-item" href="{{ route('usuarios.index') }}">
                                    <i class="bi bi-person-lines-fill me-2"></i> Usuarios
                                </a>
                            </li>
                        @endcan

                        @can('rol-list')
                            <li>
                                <a class="dropdown-item" href="{{ route('roles.index') }}">
                                    <i class="bi bi-key-fill me-2"></i> Roles
                                </a>
                            </li>
                        @endcan

                        @can('producto-list')
                            <li>
                                <a class="dropdown-item" href="{{ route('productos.index') }}">
                                    <i class="bi bi-box-seam me-2"></i> Productos
                                </a>
                            </li>
                        @endcan

                        @can('catalogo-list')
                            <li>
                                <a class="dropdown-item" href="{{ route('catalogo.index') }}">
                                    <i class="bi bi-bookmark me-2"></i> Catálogo
                                </a>
                            </li>
                        @endcan

                        @can('categoria-list')
                            <li>
                                <a class="dropdown-item" href="{{ route('categoria.index') }}">
                                    <i class="bi bi-tags me-2"></i> Categorías
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <!-- Usuario -->
                <li class="nav-item dropdown w-100 w-lg-auto">
                    @auth
                        <a class="nav-link dropdown-toggle d-flex justify-content-center align-items-center"
                        id="navbarDropdownUser"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                            <i class="bi bi-person-circle me-1 fs-5"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdownUser">
                            <li><a class="dropdown-item" href="{{ route('perfil.pedidos') }}">
                                <i class="bi bi-bag-check me-2"></i> Mis pedidos
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('perfil.edit') }}">
                                <i class="bi bi-gear me-2"></i> Mi perfil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a href="#" class="dropdown-item text-danger" 
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                                </a>
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </ul>
                    @else
                            <div class="d-flex align-items-center">
                                <a class="nav-link d-flex justify-content-center align-items-center" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right me-1 fs-5"></i> Iniciar sesión
                                </a>
                                <a class="nav-link d-flex justify-content-center align-items-center ms-2 btn btn-outline-primary btn-sm text-nowrap" href="{{ route('registro') }}">
                                    <i class="bi bi-person-plus me-1"></i> Registrarme
                                </a>
                            </div>
                        @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
(function(){
    // Inicializar con debounce, logs y manejo de errores para depuración
    function initSearch(){
        const input = document.getElementById('buscador');
        const resultsBox = document.getElementById('resultadosBusqueda');
        if(!input || !resultsBox) return;

        // ocultar inicialmente
        resultsBox.style.display = 'none';

        let timer = null;
        input.addEventListener('input', function(){
            clearTimeout(timer);
            timer = setTimeout(async () => {
                const query = input.value.trim();
                console.log('[buscador] query ->', query);

                if(query.length < 1){
                    resultsBox.innerHTML = '';
                    resultsBox.style.display = 'none';
                    return;
                }

                try{
                    const url = "{{ route('buscar.ajax') }}?search=" + encodeURIComponent(query);
                    const resp = await fetch(url, { method: 'GET' });
                    if(!resp.ok){
                        throw new Error('HTTP ' + resp.status);
                    }
                    const data = await resp.json();
                    console.log('[buscador] respuesta ->', data);

                    resultsBox.innerHTML = '';

                    if(!Array.isArray(data) || data.length === 0){
                        resultsBox.innerHTML = '<li class="list-group-item text-center">Sin resultados</li>';
                        resultsBox.style.display = 'block';
                        return;
                    }

                    for(const producto of data){
                        const categoria = producto.categoria ?? 'Sin categoría';
                        const catalogo = producto.catalogo ?? 'Sin catálogo';
                        const imagen = producto.imagen ?? '/img/sin-imagen.png';

                        resultsBox.innerHTML += `
                            <li class="list-group-item d-flex align-items-center gap-3 resultado-item" 
                                style="cursor:pointer;"
                                onclick="window.location='/producto/${producto.id}'">

                                <img src="${imagen}" 
                                     alt="${producto.nombre}" 
                                     class="rounded" 
                                     style="width: 60px; height: 60px; object-fit: cover;">

                                <div>
                                    <strong>${producto.nombre}</strong><br>
                                    <span class="text-success">$${producto.precio}</span><br>
                                    <small>Categoría: ${categoria}</small><br>
                                    <small>Catálogo: ${catalogo}</small>
                                </div>
                            </li>`;
                    }

                    resultsBox.style.display = 'block';

                }catch(err){
                    console.error('[buscador] error:', err);
                    resultsBox.innerHTML = '<li class="list-group-item text-center text-danger">Error al realizar la búsqueda</li>';
                    resultsBox.style.display = 'block';
                }

            }, 250);
        });

        // cerrar al hacer clic fuera
        document.addEventListener('click', function(e){
            const form = document.getElementById('formBuscador');
            if(!form) return;
            if(!form.contains(e.target)){
                resultsBox.style.display = 'none';
            }
        });
    }

    if(document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', initSearch);
    } else {
        initSearch();
    }
})();
</script>

<!-- Styles and search script moved to public CSS/JS (resources/css/web.css and resources/js/web.js) -->