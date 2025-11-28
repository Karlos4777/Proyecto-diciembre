<nav class="app-header navbar navbar-expand-lg app-navbar">
    <div class="container-fluid px-4 px-lg-5">

        <!-- Marca -->
        <a class="navbar-brand fw-bold text-uppercase" href="{{ url('/') }}">
            <img src="{{ asset('assets/img/nav-logo-img.png') }}" alt="DiscZone" class="nav-logo d-inline-block align-middle">
        </a>

        <!-- 🔍 Buscador principal (visible siempre en desktop, alineado a la izquierda) -->
        <form id="formBuscador" class="d-none d-lg-flex me-auto position-relative search-form" method="GET" action="{{ route('web.index') }}">
            <div class="search-wrapper position-relative w-100">
                <i class="bi bi-search search-icon-left"></i>
                <input type="text" id="buscador" class="form-control ps-5" placeholder="Buscar productos..." name="search" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" value="{{ request('search') }}">
                <!-- Campo alternativo 'texto' para compatibilidad con endpoints que lo requieran -->
                <input type="hidden" id="buscador_texto" name="texto" value="{{ request('search') }}">
            </div>
           <ul id="resultadosBusqueda" class="list-group position-absolute" role="listbox">
            </ul>
        </form>

        <!-- Botón responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido colapsable -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <!-- 🔍 Buscador mobile (dentro del collapse) -->
            <form id="formBuscadorMobile" class="d-flex d-lg-none mb-3 position-relative search-form" method="GET" action="{{ route('web.index') }}">
                <div class="search-wrapper position-relative w-100">
                    <i class="bi bi-search search-icon-left"></i>
                    <input type="text" id="buscadorMobile" class="form-control ps-5" placeholder="Buscar productos..." name="search" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" value="{{ request('search') }}">
                    <input type="hidden" name="texto" value="{{ request('search') }}">
                </div>
               <ul id="resultadosBusquedaMobile" class="list-group position-absolute" role="listbox">
                </ul>
            </form>

            <!-- Enlaces principales -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 d-flex align-items-center justify-content-center gap-2">

                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->is('web') ? 'active' : '' }}" href="{{ route('web.index') }}">Inicio</a>
                </li>

                <!-- Dropdown Catálogos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3" id="navbarDropdownCatalogo" href="#" role="button"
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
                    <a class="nav-link dropdown-toggle px-3" id="navbarDropdownCategoria" href="#" role="button"
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

            </ul>

            <!-- Sección derecha: Carrito, Sistema, Usuario -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center gap-2">
                <!-- Carrito -->
                @auth
                <li class="nav-item">
                    <a href="{{ route('carrito.mostrar') }}" class="btn btn-carrito d-flex align-items-center justify-content-center position-relative">
                        <i class="bi bi-cart-fill me-2"></i>
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center px-2" id="navbarDropdownSistema" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                <li class="nav-item dropdown">
                    @auth
                        <a class="nav-link dropdown-toggle d-flex justify-content-center align-items-center px-2"
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
                            <li><a class="dropdown-item" href="{{ route('favoritos.index') }}">
                                <i class="bi bi-heart me-2"></i> Mis favoritos
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
                        <a class="btn btn-outline-primary d-flex align-items-center justify-content-center me-2" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar sesión
                        </a>
                    @endauth
                </li>
                @guest
                <li class="nav-item">
                    <a class="btn btn-primary d-flex align-items-center justify-content-center" href="{{ route('registro') }}">
                        <i class="bi bi-person-plus me-2"></i> Registrarme
                    </a>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>


<script>
(function(){
    function initSearchInstance(inputId, resultsId, formId, loadingId){
        const input = document.getElementById(inputId);
        const resultsBox = document.getElementById(resultsId);
        const form = document.getElementById(formId);
        if(!input || !resultsBox) return;

        // Asegurar estado inicial
        resultsBox.classList.remove('visible');
        let timer = null;
        let selectedIndex = -1;

        input.addEventListener('input', function(){
            clearTimeout(timer);
            selectedIndex = -1;
            
            const query = input.value.trim();
            if(query.length < 1){
                resultsBox.innerHTML = '';
                resultsBox.classList.remove('visible');
                return;
            }
            
            // Mostrar solo mensaje de búsqueda y esperar
            resultsBox.innerHTML = '';
            const searchingLi = document.createElement('li');
            searchingLi.className = 'list-group-item text-center py-3 searching-message';
            searchingLi.innerHTML = '<div class="d-flex align-items-center justify-content-center gap-2"><span class="spinner-border spinner-border-sm text-primary" role="status"></span><span class="text-muted">Buscando productos...</span></div>';
            resultsBox.appendChild(searchingLi);
            resultsBox.classList.add('visible');
            if(loadingSpinner) loadingSpinner.classList.remove('d-none');
            
            // NO ejecutar búsqueda hasta que termine de escribir
            timer = setTimeout(fetchAndRender, 1000);
        });

        input.addEventListener('keydown', function(e){
            const items = resultsBox.querySelectorAll('.resultado-item');
            if(!items.length) return;

            if(e.key === 'ArrowDown'){
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection(items);
            } else if(e.key === 'ArrowUp'){
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(items);
            } else if(e.key === 'Enter' && selectedIndex >= 0){
                e.preventDefault();
                items[selectedIndex].click();
            }
        });

        function updateSelection(items){
            items.forEach((item, idx) => {
                item.classList.toggle('selected', idx === selectedIndex);
            });
        }

        async function fetchAndRender(){
                    const query = input.value.trim();
                    // mantener sincronizado campo alternativo para compatibilidad con endpoints que leen 'texto'
                    const hidden = form.querySelector('input[name="texto"]');
                    if(hidden) hidden.value = query;
            if(query.length < 1){
                resultsBox.innerHTML = '';
                resultsBox.classList.remove('visible');
                return;
            }

            selectedIndex = -1;

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
                    li.className = 'list-group-item text-center py-3';
                    li.innerHTML = '<i class="bi bi-search text-muted"></i> <span class="text-muted">Sin resultados</span>';
                    resultsBox.appendChild(li);
                    resultsBox.classList.add('visible');
                    return;
                }

                const frag = document.createDocumentFragment();
                
                // Helper: Formatear precio
                function formatPrice(value){
                    const num = Number(value) || 0;
                    return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
                }
                
                // Helper: Determinar estado del stock
                function getStockStatus(cantidad){
                    const stock = Number(cantidad) || 0;
                    if(stock >= 21) return { class: 'bg-success', icon: 'bi-check-circle', text: 'Disponible' };
                    if(stock >= 1) return { class: 'bg-warning text-dark', icon: 'bi-exclamation-circle', text: 'Pocas' };
                    return { class: 'bg-danger', icon: 'bi-x-circle', text: 'Agotado' };
                }
                
                // Renderizar cada producto con estructura unificada
                for(const producto of data){
                    const li = document.createElement('li');
                    li.className = 'list-group-item resultado-item p-0';

                    li.addEventListener('click', () => {
                        window.location.href = '/producto/' + producto.id;
                    });

                    // Card unificado compacto
                    const card = document.createElement('div');
                    card.className = 'product-card-unified compact';

                    // === IMAGEN CON BADGES ===
                    const imgWrapper = document.createElement('div');
                    imgWrapper.className = 'product-image-wrapper';
                    
                    const img = document.createElement('img');
                    img.src = producto.imagen ?? '/img/sin-imagen.png';
                    img.alt = producto.nombre ?? '';
                    img.className = 'product-image';
                    img.loading = 'lazy';
                    imgWrapper.appendChild(img);

                    // Badge de estado (stock)
                    const stockStatus = getStockStatus(producto.cantidad);
                    const estadoBadge = document.createElement('span');
                    estadoBadge.className = `badge ${stockStatus.class} position-absolute top-0 start-0 product-badge`;
                    estadoBadge.innerHTML = `<i class="bi ${stockStatus.icon}"></i>`;
                    imgWrapper.appendChild(estadoBadge);

                    // Badge de descuento
                    const descuento = Number(producto.descuento || 0);
                    if(descuento > 0){
                        const descBadge = document.createElement('span');
                        descBadge.className = 'badge bg-warning text-dark position-absolute top-0 end-0 product-badge';
                        descBadge.textContent = `-${descuento}%`;
                        imgWrapper.appendChild(descBadge);
                    }

                    // === CONTENIDO ===
                    const content = document.createElement('div');
                    content.className = 'product-content';

                    // Título
                    const title = document.createElement('h6');
                    title.className = 'product-title';
                    const nombreCompleto = producto.nombre ?? '';
                    title.textContent = nombreCompleto.length > 45 ? nombreCompleto.substring(0, 45) + '...' : nombreCompleto;
                    title.title = nombreCompleto;
                    content.appendChild(title);

                    // Badges de categoría/catálogo
                    const badges = document.createElement('div');
                    badges.className = 'product-badges';
                    
                    if(producto.categoria && producto.categoria !== 'Sin categoría'){
                        const catBadge = document.createElement('span');
                        catBadge.className = 'badge bg-primary product-category-badge';
                        catBadge.innerHTML = '<i class="bi bi-tags-fill me-1"></i>' + producto.categoria;
                        badges.appendChild(catBadge);
                    }
                    
                    if(producto.catalogo && producto.catalogo !== 'Sin catálogo'){
                        const cataBadge = document.createElement('span');
                        cataBadge.className = 'badge bg-danger product-catalog-badge';
                        cataBadge.innerHTML = '<i class="bi bi-bookmark-fill me-1"></i>' + producto.catalogo;
                        badges.appendChild(cataBadge);
                    }
                    content.appendChild(badges);

                    // Precios
                    const priceWrapper = document.createElement('div');
                    priceWrapper.className = 'product-price-wrapper';
                    
                    if(descuento > 0){
                        const originalPrice = document.createElement('small');
                        originalPrice.className = 'text-muted text-decoration-line-through price-original';
                        originalPrice.textContent = '$' + formatPrice(producto.precio ?? 0);
                        priceWrapper.appendChild(originalPrice);
                        
                        const finalPrice = document.createElement('span');
                        finalPrice.className = 'fw-bold text-success price-final';
                        finalPrice.textContent = '$' + formatPrice(producto.precio_con_descuento ?? producto.precio ?? 0);
                        priceWrapper.appendChild(finalPrice);
                    } else {
                        const finalPrice = document.createElement('span');
                        finalPrice.className = 'fw-bold text-success price-final';
                        finalPrice.textContent = '$' + formatPrice(producto.precio ?? 0);
                        priceWrapper.appendChild(finalPrice);
                    }
                    content.appendChild(priceWrapper);

                    // Ensamblar card
                    card.appendChild(imgWrapper);
                    card.appendChild(content);
                    li.appendChild(card);
                    frag.appendChild(li);
                }

                resultsBox.appendChild(frag);
                resultsBox.classList.add('visible');

            } catch(err){
                if(loadingSpinner) loadingSpinner.classList.add('d-none');
                resultsBox.innerHTML = '';
                const li = document.createElement('li');
                li.className = 'list-group-item text-center text-danger';
                li.textContent = 'Error al realizar la búsqueda';
                resultsBox.appendChild(li);
                resultsBox.classList.add('visible');
                console.error('[buscador] error:', err);
            }
        }

        // cerrar al hacer clic fuera del form o al presionar Escape
        document.addEventListener('click', function(e){
            if(!form) return;
            if(!form.contains(e.target)){
                resultsBox.classList.remove('visible');
            }
        });
        document.addEventListener('keydown', function(e){
            if(e.key === 'Escape'){
                resultsBox.classList.remove('visible');
                input.blur();
            }
        });
    }

    function initAllSearches(){
        // Desktop search
        initSearchInstance('buscador', 'resultadosBusqueda', 'formBuscador', 'searchLoading');
        // Mobile search
        initSearchInstance('buscadorMobile', 'resultadosBusquedaMobile', 'formBuscadorMobile', 'searchLoadingMobile');
    }

    if(document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', initAllSearches);
    } else {
        initAllSearches();
    }
})();
</script>

<!-- Styles and search script moved to public CSS/JS (resources/css/web.css and resources/js/web.js) -->