<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container px-4 px-lg-5">
    <a class="navbar-brand" href="/">DiscZone.com</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
        <!-- INICIO -->
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/') ? 'active fw-bold' : '' }}" href="/">Inicio</a>
        </li>

        <!-- ACERCA -->
        <li class="nav-item">
          <a class="nav-link {{ request()->is('acerca') ? 'active fw-bold' : '' }}" href="#">Acerca</a>
        </li>

        <!-- CATÁLOGO -->
        <li class="nav-item dropdown {{ request()->routeIs('catalogo.show') ? 'show active' : '' }}">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('catalogo.show') ? 'active fw-bold' : '' }}"
             id="navbarDropdownCatalogo" href="#" role="button" data-bs-toggle="dropdown"
             aria-expanded="{{ request()->routeIs('catalogo.show') ? 'true' : 'false' }}">
            Catálogo
          </a>
          <ul class="dropdown-menu {{ request()->routeIs('catalogo.show') ? 'show' : '' }}"
              aria-labelledby="navbarDropdownCatalogo">
            @foreach($catalogos as $catalogo)
              <li>
                <a class="dropdown-item {{ request()->is('catalogo/' . $catalogo->id) ? 'active fw-semibold' : '' }}"
                   href="{{ route('catalogo.show', $catalogo->id) }}">
                  {{ $catalogo->nombre }}
                </a>
              </li>
              @if(!$loop->last)
                <li><hr class="dropdown-divider" /></li>
              @endif
            @endforeach
          </ul>
        </li>

        <!-- CATEGORÍAS -->
        <li class="nav-item dropdown {{ request()->routeIs('categoria.show') ? 'show active' : '' }}">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('categoria.show') ? 'active fw-bold' : '' }}"
             id="navbarDropdownCategoria" href="#" role="button" data-bs-toggle="dropdown"
             aria-expanded="{{ request()->routeIs('categoria.show') ? 'true' : 'false' }}">
            Categorías
          </a>
          <ul class="dropdown-menu {{ request()->routeIs('categoria.show') ? 'show' : '' }}"
              aria-labelledby="navbarDropdownCategoria">
            @foreach($categorias as $categoria)
              <li>
                <a class="dropdown-item {{ request()->is('categoria/' . $categoria->id) ? 'active fw-semibold' : '' }}"
                   href="{{ route('categoria.show', $categoria->id) }}">
                  {{ $categoria->nombre }}
                </a>
              </li>
              @if(!$loop->last)
                <li><hr class="dropdown-divider" /></li>
              @endif
            @endforeach
          </ul>
        </li>

        <!-- PERFIL -->
        <li class="nav-item dropdown {{ request()->routeIs('perfil.*') ? 'show active' : '' }}">
          @auth
            <a class="nav-link dropdown-toggle {{ request()->routeIs('perfil.*') ? 'active fw-bold' : '' }}"
               id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown"
               aria-expanded="{{ request()->routeIs('perfil.*') ? 'true' : 'false' }}">
              <i class="bi bi-person-circle me-2"></i>{{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu {{ request()->routeIs('perfil.*') ? 'show' : '' }}"
                aria-labelledby="navbarDropdownUser">
              <li>
                <a class="dropdown-item {{ request()->routeIs('perfil.pedidos') ? 'active fw-semibold' : '' }}"
                   href="{{ route('perfil.pedidos') }}">Mis pedidos</a>
              </li>
              <li><hr class="dropdown-divider" /></li>
              <li>
                <a class="dropdown-item {{ request()->routeIs('perfil.edit') ? 'active fw-semibold' : '' }}"
                   href="{{ route('perfil.edit') }}">Mi perfil</a>
              </li>
            </ul>
          @else
            <a class="nav-link {{ request()->routeIs('login') ? 'active fw-bold' : '' }}"
               href="{{ route('login') }}">Iniciar sesión</a>
          @endauth
        </li>
      </ul>

      <!-- CARRITO -->
      <a href="{{ route('carrito.mostrar') }}" class="btn btn-outline-dark">
        <i class="bi-cart-fill me-1"></i> Pedido
        <span class="badge bg-dark text-white ms-1 rounded-pill">{{ $cartCount ?? 0 }}</span>
      </a>
    </div>
  </div>
</nav>