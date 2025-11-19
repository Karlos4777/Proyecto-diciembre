// Web frontend JS consolidated
// Moved from inline scripts in nav.blade.php and carrusel.blade.php

document.addEventListener('DOMContentLoaded', () => {
    // Enhanced Carrusel handlers (snap, autoplay, touch, keyboard)
    document.querySelectorAll('.carrusel-wrapper').forEach(wrapper => {
        const carrusel = wrapper.querySelector('.productos-carrusel');
        const nextBtn = wrapper.querySelector('.carousel-btn.right');
        const prevBtn = wrapper.querySelector('.carousel-btn.left');
        const items = Array.from(carrusel ? carrusel.querySelectorAll('.producto-card') : []);
        let autoplayTimer = null;
        const AUTOPLAY_DELAY = 3500;

        function getCenterIndex() {
            const r = carrusel.getBoundingClientRect();
            const centerX = r.left + r.width / 2;
            let closest = 0;
            let minDist = Infinity;
            items.forEach((it, idx) => {
                const ir = it.getBoundingClientRect();
                const itemCenter = ir.left + ir.width / 2;
                const dist = Math.abs(itemCenter - centerX);
                if (dist < minDist) {
                    minDist = dist; closest = idx;
                }
            });
            return closest;
        }

        function goToIndex(idx) {
            if (!items[idx]) return;
            const item = items[idx];
            const targetLeft = item.offsetLeft - (carrusel.clientWidth - item.offsetWidth) / 2;
            carrusel.scrollTo({ left: targetLeft, behavior: 'smooth' });
        }

        function next() {
            if (!items.length) return;
            const idx = getCenterIndex();
            const nextIdx = Math.min(items.length - 1, idx + 1);
            goToIndex(nextIdx);
        }

        function prev() {
            if (!items.length) return;
            const idx = getCenterIndex();
            const prevIdx = Math.max(0, idx - 1);
            goToIndex(prevIdx);
        }

        if (nextBtn) {
            nextBtn.innerHTML = '<i class="bi bi-chevron-right" style="font-size: 1.3rem;"></i>';
            nextBtn.addEventListener('click', (e) => { e.preventDefault(); next(); });
        }
        if (prevBtn) {
            prevBtn.innerHTML = '<i class="bi bi-chevron-left" style="font-size: 1.3rem;"></i>';
            prevBtn.addEventListener('click', (e) => { e.preventDefault(); prev(); });
        }

        // Autoplay with pause on hover/focus
        function startAutoplay() {
            stopAutoplay();
            autoplayTimer = setInterval(() => { next(); }, AUTOPLAY_DELAY);
        }
        function stopAutoplay() {
            if (autoplayTimer) { clearInterval(autoplayTimer); autoplayTimer = null; }
        }

        wrapper.addEventListener('mouseenter', stopAutoplay);
        wrapper.addEventListener('mouseleave', startAutoplay);
        wrapper.addEventListener('focusin', stopAutoplay);
        wrapper.addEventListener('focusout', startAutoplay);

        // Pause when page hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) stopAutoplay(); else startAutoplay();
        });

        // Touch swipe support
        let touchStartX = null;
        wrapper.addEventListener('touchstart', (ev) => { stopAutoplay(); touchStartX = ev.touches[0].clientX; }, { passive: true });
        wrapper.addEventListener('touchend', (ev) => {
            if (touchStartX === null) return; const dx = ev.changedTouches[0].clientX - touchStartX; const TH = 40;
            if (dx > TH) prev(); else if (dx < -TH) next(); touchStartX = null; startAutoplay();
        });

        // Keyboard support (left/right when not typing)
        document.addEventListener('keydown', (e) => {
            const active = document.activeElement;
            if (active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.isContentEditable)) return;
            if (e.key === 'ArrowRight') { next(); }
            if (e.key === 'ArrowLeft') { prev(); }
        });

        // Start autoplay initially
        startAutoplay();
    });

    // Buscador AJAX (from nav.blade.php)
    try {
        const input = document.getElementById('buscador');
        const resultados = document.getElementById('resultadosBusqueda');
        const form = document.getElementById('formBuscador');
        const storageUrl = window.__app_storage_url__ || '';
        const defaultImg = window.__app_default_img__ || '';
        const priceFormatter = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        if (input) {
            // helper: escape regex chars
            const escapeRegex = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

            const highlight = (text, query) => {
                if (!query) return text;
                const words = query.split(/\s+/).filter(Boolean).map(w => escapeRegex(w));
                if (!words.length) return text;
                const re = new RegExp('(' + words.join('|') + ')', 'gi');
                return String(text).replace(re, '<mark>$1</mark>');
            };

            const doSearchRealtime = async (q) => {
                const query = (q || '').trim();
                const spinner = document.getElementById('buscadorSpinner');

                if (query.length < 2) {
                    if (resultados) {
                        resultados.style.display = 'none';
                        resultados.innerHTML = '';
                    }
                    return;
                }

                // normalize helper (remove accents) for client-side matching
                const normalizeText = (s) => (s || '').toString().normalize('NFD').replace(/\u0300-\u036f/g, '').replace(/[\u0000-\u001f]/g, '').replace(/[\u0300-\u036f]/g, '').toLowerCase();
                const normQuery = normalizeText(query);

                try {
                    if (spinner) spinner.classList.remove('d-none');
                    const response = await fetch(`/buscar-productos?search=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    if (!resultados) return;

                    // order by relevance: products whose name contains the query go first
                    try {
                        data.sort((a, b) => {
                            const aNameHas = normalizeText(a.nombre).includes(normQuery);
                            const bNameHas = normalizeText(b.nombre).includes(normQuery);
                            if (aNameHas && !bNameHas) return -1;
                            if (!aNameHas && bNameHas) return 1;
                            return 0;
                        });
                    } catch (e) {
                        // ignore sorting errors
                    }

                    if (data.length === 0) {
                        resultados.innerHTML = '<li class="list-group-item text-muted"><i class="bi bi-search me-2"></i>No se encontraron productos</li>';
                    } else {
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
                    <li class="list-group-item py-2">
                        <a href="/producto/${p.id}" class="d-flex align-items-center text-decoration-none text-body">
                           <img src="${safeImg}" 
                               alt="${p.nombre}" 
                               style="width: 56px; height: 56px; object-fit: cover; object-position: center; border-radius: 6px; margin-right: 12px; background-color: #f5f5f5; padding: 2px; display: block;"
                               onerror="this.onerror=null;this.src='${defaultImg}'">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-baseline justify-content-between">
                                    <div>
                                        <div class="fw-semibold">${nombreHtml}</div>
                                        <small class="text-muted">${categoriaHtml} • ${catalogoHtml}</small>
                                    </div>
                                    <div class="text-end ms-3">
                                        ${tieneDscto ? `<div><del class="text-muted">$${priceFormatter.format(parseFloat(precioOriginal))}</del></div>` : ''}
                                        <div class="fw-bold text-success">$${priceFormatter.format(parseFloat(displayPrice))}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                `;
                        }).join('');
                    }

                    resultados.style.display = 'block';

                    // setup keyboard navigation and hover interactions
                    let currentIndex = -1;
                    const updateActive = () => {
                        const items = resultados.querySelectorAll('li');
                        items.forEach((it, idx) => {
                            if (idx === currentIndex) it.classList.add('active'); else it.classList.remove('active');
                        });
                        const activeEl = resultados.querySelector('li.active');
                        if (activeEl) activeEl.scrollIntoView({ block: 'nearest' });
                    };

                    const items = resultados.querySelectorAll('li');
                    items.forEach((li, idx) => {
                        li.addEventListener('mouseenter', () => { currentIndex = idx; updateActive(); });
                        li.addEventListener('mouseleave', () => { currentIndex = -1; updateActive(); });
                        // prevent default focus behavior on click - navigation handled by anchor
                    });
                } catch (error) {
                    console.error('Error en la búsqueda:', error);
                } finally {
                    if (spinner) spinner.classList.add('d-none');
                }
            };

            // keyboard navigation - only add once, outside doSearchRealtime
            let currentIndex = -1;
            const updateActive = () => {
                const items = resultados.querySelectorAll('li');
                items.forEach((it, idx) => {
                    if (idx === currentIndex) it.classList.add('active'); else it.classList.remove('active');
                });
                const activeEl = resultados.querySelector('li.active');
                if (activeEl) activeEl.scrollIntoView({ block: 'nearest' });
            };

            input.addEventListener('keydown', (ev) => {
                if (!resultados || resultados.style.display === 'none') return;
                const links = resultados.querySelectorAll('li a');
                if (!links.length) return;
                if (ev.key === 'ArrowDown') {
                    ev.preventDefault();
                    currentIndex = Math.min(links.length - 1, currentIndex + 1);
                    updateActive();
                } else if (ev.key === 'ArrowUp') {
                    ev.preventDefault();
                    currentIndex = Math.max(-1, currentIndex - 1);
                    updateActive();
                } else if (ev.key === 'Enter') {
                    if (currentIndex >= 0) {
                        ev.preventDefault();
                        const a = resultados.querySelectorAll('li a')[currentIndex];
                        if (a) window.location.href = a.href;
                    } else {
                        if (form) form.submit();
                    }
                } else if (ev.key === 'Escape') {
                    resultados.style.display = 'none';
                }
            });

            const debounced = (fn, wait = 250) => {
                let t = null;
                return (...args) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn(...args), wait);
                };
            };

            const handler = debounced((e) => {
                const q = input.value;
                // If Enter pressed while composing, let form submit
                if (e && e.inputType === 'insertLineBreak') { if (form) form.submit(); return; }
                doSearchRealtime(q);
            }, 180);

            input.addEventListener('input', handler);

            document.addEventListener('click', (e) => {
                if (!e.target.closest('#buscador') && !e.target.closest('#resultadosBusqueda')) {
                    if (resultados) resultados.style.display = 'none';
                }
            });
        }
    } catch (e) {
        console.error('Error initializing buscador:', e);
    }

});
