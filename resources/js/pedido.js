// JS enhancements for the pedidos index view
document.addEventListener('DOMContentLoaded', function () {
    // Toggle icon rotation when collapse is shown/hidden
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            // find icon inside button (if any)
            const icon = btn.querySelector('.toggle-icon');
            const target = btn.getAttribute('data-bs-target') || btn.getAttribute('href');
            if (!target) return;
            const el = document.querySelector(target);
            if (!el) return;

            // Bootstrap will toggle collapse; we can react to events
            // but here toggle a class for immediate user feedback
            if (icon) icon.classList.toggle('rotate');
        });
    });

    // Smooth scroll to details row when opened using bootstrap events
    document.querySelectorAll('.collapse').forEach(function (el) {
        el.addEventListener('shown.bs.collapse', function (ev) {
            // scroll so that the details row is visible (small offset)
            const rect = el.getBoundingClientRect();
            const absoluteTop = window.scrollY + rect.top;
            const offset = Math.max(0, absoluteTop - 120);
            window.scrollTo({ top: offset, behavior: 'smooth' });
        });

        // when hiding, ensure any rotate icon is reset
        el.addEventListener('hidden.bs.collapse', function (ev) {
            // find the corresponding toggle button by data-bs-target
            const id = '#' + el.id;
            const btn = document.querySelector('[data-bs-target="' + id + '"]');
            if (btn) {
                const icon = btn.querySelector('.toggle-icon');
                if (icon) icon.classList.remove('rotate');
            }
        });
    });

    // Improve table responsiveness: collapse long text in small screens
    function adjustPedidoTable() {
        document.querySelectorAll('.pedido-table td').forEach(function(td){
            if (td.textContent.length > 40 && window.innerWidth < 576) {
                td.style.whiteSpace = 'nowrap';
                td.style.overflow = 'hidden';
                td.style.textOverflow = 'ellipsis';
            } else {
                td.style.whiteSpace = '';
                td.style.overflow = '';
                td.style.textOverflow = '';
            }
        });
    }
    window.addEventListener('resize', adjustPedidoTable);
    adjustPedidoTable();
});
