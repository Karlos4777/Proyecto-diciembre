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
            <form id="formBuscador" class="d-flex mx-auto position-relative w-lg-50 search-form" method="GET" action="{{ route('web.index') }}">
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
    function initSearch(){
        const input = document.getElementById('buscador');
        const resultsBox = document.getElementById('resultadosBusqueda');
        const form = document.getElementById('formBuscador');
        if(!input || !resultsBox) return;

        // Asegurar estado inicial
        resultsBox.classList.remove('visible');

        let timer = null;
        input.addEventListener('input', function(){
            clearTimeout(timer);
            timer = setTimeout(fetchAndRender, 250);
        });

        async function fetchAndRender(){
            const query = input.value.trim();
            if(query.length < 1){
                resultsBox.innerHTML = '';
                resultsBox.classList.remove('visible');
                return;
            }

            try{
                const url = "{{ url('/buscar-productos') }}?search=" + encodeURIComponent(query);
                const resp = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' }, cache: 'no-store' });
                if(!resp.ok){
                    const txt = await resp.text().catch(()=>null);
                    throw new Error('HTTP ' + resp.status + ' - ' + (txt||''));
                }
                const data = await resp.json();

                resultsBox.innerHTML = '';

                if(!Array.isArray(data) || data.length === 0){
                    const li = document.createElement('li');
                    li.className = 'list-group-item text-center';
                    li.textContent = 'Sin resultados';
                    resultsBox.appendChild(li);
                    resultsBox.classList.add('visible');
                    return;
                }

                const frag = document.createDocumentFragment();
                for(const producto of data){
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex align-items-center gap-3 resultado-item';

                    li.addEventListener('click', () => {
                        window.location.href = '/producto/' + producto.id;
                    });

                    const img = document.createElement('img');
                    img.src = producto.imagen ?? '/img/sin-imagen.png';
                    img.alt = producto.nombre ?? '';
                    img.className = 'rounded';

                    const div = document.createElement('div');
                    const strong = document.createElement('strong');
                    strong.textContent = producto.nombre ?? '';
                    const br = document.createElement('br');
                    function formatPrice(value){
                        const num = Number(value) || 0;
                        return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
                    }

                    const priceWrapper = document.createElement('div');
                    priceWrapper.className = 'precio-wrapper';
                    // Mostrar descuento si aplica
                    const descuento = Number(producto.descuento || 0);
                    if(descuento > 0){
                        const original = document.createElement('div');
                        original.className = 'text-muted original-price';
                        original.textContent = '$' + formatPrice(producto.precio ?? 0);

                        const discounted = document.createElement('div');
                        discounted.className = 'text-success fw-bold';
                        discounted.textContent = '$' + formatPrice(producto.precio_con_descuento ?? producto.precio ?? 0);

                        const badge = document.createElement('span');
                        badge.className = 'badge bg-danger ms-2 discount-badge';
                        badge.textContent = '-' + descuento + '%';

                        priceWrapper.appendChild(original);
                        const discountedRow = document.createElement('div');
                        discountedRow.appendChild(discounted);
                        discountedRow.appendChild(badge);
                        priceWrapper.appendChild(discountedRow);
                    } else {
                        const price = document.createElement('div');
                        price.className = 'text-success fw-bold';
                        price.textContent = '$' + formatPrice(producto.precio ?? 0);
                        priceWrapper.appendChild(price);
                    }

                    const smallCat = document.createElement('small');
                    smallCat.textContent = 'Categoría: ' + (producto.categoria ?? 'Sin categoría');
                    const smallCat2 = document.createElement('small');
                    smallCat2.textContent = ' Catálogo: ' + (producto.catalogo ?? 'Sin catálogo');

                    div.appendChild(strong);
                    div.appendChild(br);
                    div.appendChild(priceWrapper);
                    div.appendChild(document.createElement('br'));
                    div.appendChild(smallCat);
                    div.appendChild(document.createElement('br'));
                    div.appendChild(smallCat2);

                    li.appendChild(img);
                    li.appendChild(div);
                    frag.appendChild(li);
                }

                resultsBox.appendChild(frag);
                resultsBox.classList.add('visible');

            } catch(err){
                resultsBox.innerHTML = '';
                const li = document.createElement('li');
                li.className = 'list-group-item text-center text-danger';
                li.textContent = 'Error al realizar la búsqueda';
                resultsBox.appendChild(li);
                resultsBox.classList.add('visible');
                console.error('[buscador] error:', err);
            }
        }

        // cerrar al hacer clic fuera del formBuscador
        document.addEventListener('click', function(e){
            if(!form) return;
            if(!form.contains(e.target)){
                resultsBox.classList.remove('visible');
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