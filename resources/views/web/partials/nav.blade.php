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
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link {{ request()->is('web') ? 'active' : '' }}" href="{{ route('web.index') }}">Inicio</a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link" href="#">Contacto</a>
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
                            @if(!$loop->last)
                                <li><hr class="dropdown-divider" /></li>
                            @endif
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
                            @if(!$loop->last)
                                <li><hr class="dropdown-divider" /></li>
                            @endif
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
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">
                                    <i class="bi bi-shield-lock me-2"></i> Seguridad
                                </a>
                                <ul class="dropdown-menu">
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
                                </ul>
                            </li>
                        @endcanany

                        <!-- Almacén -->
                        @canany(['producto-list', 'catalogo-list', 'categoria-list'])
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">
                                    <i class="bi bi-archive-fill me-2"></i> Almacén
                                </a>
                                <ul class="dropdown-menu">
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
                                                <i class="bi bi-bookmark me-2"></i> Catálogos
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
                    </ul>
                </li>
                @endcanany
            </ul>

            <!-- Sección derecha -->
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Carrito -->
                <li class="nav-item me-3">
                    <a href="{{ route('carrito.mostrar') }}" class="btn btn-outline-dark position-relative">
                        <i class="bi bi-cart-fill me-1"></i>
                        Pedido
                        @php
                            $cantidad = session('carrito') ? array_sum(array_column(session('carrito'), 'cantidad')) : 0;
                        @endphp
                        <span class="badge bg-dark text-white ms-1 rounded-pill">{{ $cantidad }}</span>
                    </a>
                </li>

                <!-- Dropdown Usuario -->
                <li class="nav-item dropdown">
                    @auth
                        <a class="nav-link dropdown-toggle d-flex align-items-center" id="navbarDropdownUser"
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1 fs-5"></i>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdownUser">
                            <li>
                                <a class="dropdown-item" href="{{ route('perfil.pedidos') }}">
                                    <i class="bi bi-bag-check me-2"></i> Mis pedidos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('perfil.edit') }}">
                                    <i class="bi bi-gear me-2"></i> Mi perfil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a href="#" class="dropdown-item text-danger" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                                </a>
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </ul>
                    @else
                        <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1 fs-5"></i>
                            <span>Iniciar sesión</span>
                        </a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ✅ CSS -->
<style>
/* Submenú estilo */
.app-header.navbar {
  position: sticky;
  top: 0;
  z-index: 1030;
}
.app-main {
  margin-top: 0 !important;
}
.dropdown-submenu {
    position: relative;
}
.dropdown-submenu > .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
    display: none;
}
.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}
.dropdown-toggle::after {
    margin-left: .3em;
}
.dropdown-item:hover {
    background-color: #f8f9fa;
}
.navbar-nav .nav-link.active {
    font-weight: bold;
    color: #0d6efd !important;
}
</style>

<!-- ✅ JS -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Manejo de submenús
    document.querySelectorAll('.dropdown-submenu > a').forEach(function(element){
        element.addEventListener('click', function(e){
            let submenu = this.nextElementSibling;
            if(submenu && submenu.classList.contains('dropdown-menu')){
                e.preventDefault();
                submenu.classList.toggle('show');
            }
        });
    });

    document.addEventListener('click', function (e) {
        document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
            if (!menu.parentNode.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    });
});
</script>
