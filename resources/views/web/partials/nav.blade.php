<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#!">DiscZone.com</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Acerca</a>
                </li>

                <!-- Dropdown Tipo -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownTipo" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Tipo</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownTipo">
                        <li><a class="dropdown-item" href="#">Vinilos</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#">CD's</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#">Casetes</a></li>
                    </ul>
                </li>

                <!-- Dropdown Categorías dinámicas -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownCategoria" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Categorías</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownCategoria">
                        @foreach($categorias as $categoria)
                            <li>
                                <a class="dropdown-item" href="{{ route('categoria.show', $categoria->id) }}">
                                    {{ $categoria->nombre }}
                                </a>
                            </li>
                            @if(!$loop->last)
                                <li><hr class="dropdown-divider" /></li>
                            @endif
                        @endforeach
                    </ul>
                </li>

                <!-- Dropdown Usuario -->
                <li class="nav-item dropdown">
                    @auth
                        <a class="nav-link dropdown-toggle" id="navbarDropdownUser" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">{{ auth()->user()->name }}</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownUser">
                            <li><a class="dropdown-item" href="{{ route('perfil.pedidos') }}">Mis pedidos</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="{{ route('perfil.edit') }}">Mi perfil</a></li>
                        </ul>
                    @else
                        <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                    @endauth
                </li>
            </ul>

            <!-- Botón Carrito -->
            <a href="{{ route('carrito.mostrar') }}" class="btn btn-outline-dark">
                <i class="bi-cart-fill me-1"></i>
                Pedido
                <span class="badge bg-dark text-white ms-1 rounded-pill">
                    {{ session('carrito') ? array_sum(array_column(session('carrito'), 'cantidad')) : 0 }}
                </span>
            </a>
        </div>
    </div>
</nav>
