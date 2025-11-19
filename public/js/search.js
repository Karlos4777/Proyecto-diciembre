// Búsqueda en tiempo real - Simplificado y robusto
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('buscador');
    const resultados = document.getElementById('resultadosBusqueda');
    const spinner = document.getElementById('buscadorSpinner');
    const form = document.getElementById('formBuscador');

    if (!input || !resultados || !spinner) return;

    const storageUrl = window.__app_storage_url__ || '';
    const defaultImg = window.__app_default_img__ || '';
    const priceFormatter = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    let searchTimeout;
    let currentIndex = -1;

    const highlight = (text, query) => {
        if (!query || !text) return text;
        const words = query.trim().split(/\s+/).filter(Boolean);
        let result = String(text);
        words.forEach(word => {
            const re = new RegExp(`(${word.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            result = result.replace(re, '<mark>$1</mark>');
        });
        return result;
    };

    const renderResults = (data, query) => {
        if (data.length === 0) {
            resultados.innerHTML = '<li class="list-group-item text-muted text-center py-3"><i class="bi bi-search me-2"></i>No se encontraron productos</li>';
            return;
        }

        resultados.innerHTML = data.map(p => {
            let imgSrc = defaultImg;
            if (p.imagen) {
                if (/^https?:\/\//i.test(p.imagen)) {
                    imgSrc = p.imagen;
                } else if (p.imagen.startsWith('/storage')) {
                    imgSrc = p.imagen;
                } else {
                    imgSrc = storageUrl + '/' + p.imagen;
                }
            }

            const safeImg = encodeURI(imgSrc);
            const displayPrice = p.precio_con_descuento ? p.precio_con_descuento : p.precio;
            const precioOriginal = p.precio;
            const tieneDscto = p.descuento > 0;

            const nombreHtml = highlight(p.nombre, query);
            const categoriaHtml = highlight(p.categoria || '', query);
            const catalogoHtml = highlight(p.catalogo || '', query);

            return `
                <li class="list-group-item py-3 border-bottom">
                    <a href="/producto/${p.id}" class="d-flex align-items-center text-decoration-none text-dark" style="gap: 12px;">
                        <img src="${safeImg}" 
                             alt="${p.nombre}" 
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; background-color: #f5f5f5; flex-shrink: 0;"
                             onerror="this.src='${defaultImg}'">
                        <div style="flex-grow: 1;">
                            <div class="fw-semibold small">${nombreHtml}</div>
                            <small class="text-muted d-block">${categoriaHtml} • ${catalogoHtml}</small>
                            <div class="fw-bold text-success mt-2">
                                $${priceFormatter.format(parseFloat(displayPrice))}
                                ${tieneDscto ? `<span class="text-muted ms-2" style="font-size: 0.85em; text-decoration: line-through;">$${priceFormatter.format(parseFloat(precioOriginal))}</span>` : ''}
                            </div>
                        </div>
                    </a>
                </li>
            `;
        }).join('');
    };

    const doSearch = async (query) => {
        query = (query || '').trim();

        if (query.length < 2) {
            resultados.innerHTML = '';
            resultados.style.display = 'none';
            currentIndex = -1;
            return;
        }

        try {
            spinner.classList.remove('d-none');
            
            const response = await fetch(`/buscar-productos?search=${encodeURIComponent(query)}`);
            const data = await response.json();

            // Ordenar por relevancia (nombre primero)
            const normalizeText = (s) => (s || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
            const normQuery = normalizeText(query);
            data.sort((a, b) => {
                const aHas = normalizeText(a.nombre).includes(normQuery);
                const bHas = normalizeText(b.nombre).includes(normQuery);
                return aHas === bHas ? 0 : aHas ? -1 : 1;
            });

            renderResults(data, query);
            if (data.length > 0) resultados.style.display = 'block';
            currentIndex = -1;
        } catch (error) {
            console.error('Error en búsqueda:', error);
        } finally {
            spinner.classList.add('d-none');
        }
    };

    // Input event - búsqueda en tiempo real
    input.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => doSearch(input.value), 300);
    });

    // Keyboard navigation
    input.addEventListener('keydown', (e) => {
        if (resultados.style.display === 'none') return;

        const items = resultados.querySelectorAll('li');
        if (items.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentIndex = Math.min(items.length - 1, currentIndex + 1);
            items[currentIndex]?.classList.add('bg-light');
            if (currentIndex > 0) items[currentIndex - 1]?.classList.remove('bg-light');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentIndex = Math.max(-1, currentIndex - 1);
            if (currentIndex >= 0) {
                items[currentIndex]?.classList.add('bg-light');
                if (currentIndex < items.length - 1) items[currentIndex + 1]?.classList.remove('bg-light');
            } else {
                items.forEach(it => it.classList.remove('bg-light'));
            }
        } else if (e.key === 'Enter') {
            if (currentIndex >= 0) {
                e.preventDefault();
                const link = items[currentIndex]?.querySelector('a');
                if (link) window.location.href = link.href;
            }
        } else if (e.key === 'Escape') {
            resultados.style.display = 'none';
            currentIndex = -1;
        }
    });

    // Hover en items
    resultados.addEventListener('mouseenter', (e) => {
        if (e.target.closest('li')) {
            resultados.querySelectorAll('li').forEach(li => li.classList.remove('bg-light'));
            e.target.closest('li').classList.add('bg-light');
        }
    }, true);

    resultados.addEventListener('mouseleave', (e) => {
        if (e.target.closest('li')) {
            resultados.querySelectorAll('li').forEach(li => li.classList.remove('bg-light'));
            currentIndex = -1;
        }
    }, true);

    // Click fuera - cerrar dropdown
    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !resultados.contains(e.target)) {
            resultados.style.display = 'none';
            currentIndex = -1;
        }
    });
});
