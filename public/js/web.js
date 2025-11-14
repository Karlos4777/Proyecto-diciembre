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
            input.addEventListener('keyup', async (e) => {
                const query = input.value.trim();

                if (e.key === 'Enter') {
                    if (form) form.submit();
                    return;
                }

                if (query.length < 2) {
                    if (resultados) {
                        resultados.style.display = 'none';
                        resultados.innerHTML = '';
                    }
                    return;
                }

                try {
                    const response = await fetch(`/buscar-productos?search=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    if (!resultados) return;

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

                            return `
                    <li class="list-group-item">
                        <a href="/producto/${p.id}" class="d-flex align-items-center">
                       <img src="${safeImg}" 
                           alt="${p.nombre}" 
                           style="width: 50px; height: 50px; object-fit: contain; object-position: center; border-radius: 4px; margin-right: 10px; background-color: #f5f5f5; padding: 2px; display: block;"
                           onerror="this.onerror=null;this.src='${defaultImg}'">
                            <div>
                                <strong>${p.nombre}</strong> 
                                ${tieneDscto ? `- <del style="color: #999; font-size: 0.85rem;">$${priceFormatter.format(parseFloat(precioOriginal))}</del>` : ''}
                                - <span class="fw-bold" style="color: #28a745;">$${priceFormatter.format(parseFloat(displayPrice))}</span> 
                                ${tieneDscto ? `<span class="badge bg-warning text-dark" style="margin-left: 5px;">-${p.descuento}%</span>` : ''}
                                <br>
                                <small class="text-muted">${p.categoria} | ${p.catalogo}</small><br>
                                <span class="badge ${
                                    p.estado === 'Disponible' ? 'bg-success' :
                                    p.estado === 'Pocas unidades' ? 'bg-warning text-dark' :
                                    'bg-danger'
                                }"><i class="bi ${
                                    p.estado === 'Disponible' ? 'bi-check-circle' :
                                    p.estado === 'Pocas unidades' ? 'bi-exclamation-circle' :
                                    'bi-x-circle'
                                } me-1"></i>${p.estado}</span>
                            </div>
                        </a>
                    </li>
                `;
                        }).join('');
                    }

                    resultados.style.display = 'block';
                } catch (error) {
                    console.error('Error en la búsqueda:', error);
                }
            });

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
