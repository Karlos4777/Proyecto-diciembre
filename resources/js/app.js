import './bootstrap';
import './search';

document.addEventListener('DOMContentLoaded', function () {
    // Manejo de submenús dentro de dropdowns Bootstrap
    document.querySelectorAll('.dropdown-submenu > a').forEach(function (element) {
        element.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Cierra otros submenús abiertos
            document.querySelectorAll('.dropdown-submenu .dropdown-menu.show').forEach(function (submenu) {
                if (submenu !== this.nextElementSibling) {
                    submenu.classList.remove('show');
                }
            }, this);

            // Alterna el actual
            this.nextElementSibling.classList.toggle('show');
        });
    });

    // Cierra submenús al cerrar dropdown principal
    document.querySelectorAll('.dropdown').forEach(function (dropdown) {
        dropdown.addEventListener('hidden.bs.dropdown', function () {
            this.querySelectorAll('.dropdown-menu.show').forEach(function (submenu) {
                submenu.classList.remove('show');
            });
        });
    });
});

