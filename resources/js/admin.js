document.addEventListener('DOMContentLoaded', function () {
    // Helper to add a class if element exists
    const addIf = (id, className) => {
        try {
            const el = document.getElementById(id);
            if (el && !el.classList.contains(className)) el.classList.add(className);
        } catch (e) {
            // ignore
        }
    };

    // Common menu activations observed across views
    // Storage/Inventory menu items
    addIf('mnuAlmacen', 'menu-open');
    addIf('itemProducto', 'active');
    addIf('itemCategoria', 'active');
    addIf('itemCatalogo', 'active');

    // Security menu items
    addIf('mnuSeguridad', 'menu-open');
    addIf('itemUsuario', 'active');
    addIf('itemRole', 'active');

    // Orders menu items
    addIf('mnuPedidos', 'active');

    // Dropdown-submenu behaviour (moved from plantilla header)
    document.querySelectorAll('.dropdown-submenu > a').forEach(function (element) {
        element.addEventListener('click', function (e) {
            const submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains('dropdown-menu')) {
                e.preventDefault();
                submenu.classList.toggle('show');
            }
        });
    });

    document.addEventListener('click', function (e) {
        document.querySelectorAll('.dropdown-menu.show').forEach(function (menu) {
            if (!menu.parentNode.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    });
});
