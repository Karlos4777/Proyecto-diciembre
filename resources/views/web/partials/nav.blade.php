<nav class="app-header navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid px-4 px-lg-5">

        <!-- Marca -->
        <a class="navbar-brand fw-bold text-uppercase" href="{{ url('/') }}">DiscZone.com</a>

        <!-- Botón responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido colapsable -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <!-- Enlaces principales -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 text-center text-lg-start">
                
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

                <!-- Dropdown Sistema -->
                @canany(['user-list', 'rol-list', 'producto-list', 'catalogo-list', 'categoria-list'])
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownSistema" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear-fill me-1"></i> Sistema
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownSistema">

                        <!-- Dashboard -->
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        <!-- Seguridad -->
                        @canany(['user-list', 'rol-list'])
                        <li class="dropend">
                            <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-shield-lock me-2"></i> Seguridad
                            </a>
                            <ul class="dropdown-menu">
                                @can('user-list')
                                <li><a class="dropdown-item" href="{{ route('usuarios.index') }}">
                                    <i class="bi bi-person-lines-fill me-2"></i> Usuarios
                                </a></li>
                                @endcan
                                @can('rol-list')
                                <li><a class="dropdown-item" href="{{ route('roles.index') }}">
                                    <i class="bi bi-key-fill me-2"></i> Roles
                                </a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        <!-- Gestión / Almacén -->
                        @canany(['producto-list', 'catalogo-list', 'categoria-list'])
                        <li class="dropend">
                            <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-archive-fill me-2"></i> Gestión
                            </a>
                            <ul class="dropdown-menu">
                                @can('producto-list')
                                <li><a class="dropdown-item" href="{{ route('productos.index') }}">
                                    <i class="bi bi-box-seam me-2"></i> Productos
                                </a></li>
                                @endcan
                                @can('catalogo-list')
                                <li><a class="dropdown-item" href="{{ route('catalogo.index') }}">
                                    <i class="bi bi-bookmark me-2"></i> Catálogo
                                </a></li>
                                @endcan
                                @can('categoria-list')
                                <li><a class="dropdown-item" href="{{ route('categoria.index') }}">
                                    <i class="bi bi-tags me-2"></i> Categorías
                                </a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                    </ul>
                </li>
                @endcanany

            </ul>

            <!-- Carrito y Usuario -->
            <ul class="navbar-nav ms-auto align-items-center flex-lg-row flex-column text-center mt-3 mt-lg-0">

                <!-- Carrito -->
                @auth
                <li class="nav-item mb-2 mb-lg-0">
                    <a href="{{ route('carrito.mostrar') }}" class="btn btn-outline-dark position-relative w-100 w-lg-auto">
                        <i class="bi bi-cart-fill me-1"></i> Pedido
                        @php
                            $registro = \App\Models\Carrito::where('user_id', auth()->id())->first();
                            $cantidad = $registro ? array_sum(array_column($registro->contenido ?? [], 'cantidad')) : 0;
                        @endphp
                        <span class="badge bg-dark text-white ms-1 rounded-pill">{{ $cantidad }}</span>
                    </a>
                </li>
                @endauth

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
                        <a class="nav-link d-flex justify-content-center align-items-center" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1 fs-5"></i> Iniciar sesión
                        </a>
                    @endauth
                </li>

            </ul>
        </div>
    </div>
</nav>

<script>
document.querySelectorAll('.dropdown-menu a.dropdown-toggle').forEach(function(element) {
    element.addEventListener('click', function(e) {
        let submenu = this.nextElementSibling;
        if (!submenu.classList.contains('show')) {
            // Cerrar otros submenús abiertos
            let submenus = this.closest('.dropdown-menu').querySelectorAll('.show');
            submenus.forEach(function(sub) { sub.classList.remove('show'); });
        }
        submenu.classList.toggle('show');
        e.stopPropagation();
        e.preventDefault();
    });
});
</script>