<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="ArtCode" />
    <meta name="description" content="Sistema | ArtCode.com" />
    <title>@yield('titulo', 'Panel de Administración - ArtCode')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />

    <!-- Fuente principal -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />

    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />

    <!-- Estilos principales -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />


    @stack('estilos')

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--bs-body-bg);
        }

        /* Contenedor principal (ajustado y centrado) */
        .app-main {
            flex: 1; /* Ocupa todo el espacio disponible entre nav y footer */
            display: flex;
            justify-content: center; /* Centra horizontalmente */
            align-items: center;     /* Centra verticalmente */
            padding: 2rem;           /* Espaciado interior */
        }

        /* Limita el ancho máximo del contenido */
        .app-main > * {
            width: 100%;
            max-width: 1600px; /* Evita que el contenido sea demasiado ancho */
        }

        /* Asegura que el footer siempre quede al fondo */
        footer {
            margin-top: auto;
        }

        /* Ajuste visual opcional para pantallas pequeñas */
        @media (max-width: 768px) {
            .app-main {
                padding: 1rem;
                align-items: flex-start; /* Evita que el contenido quede demasiado alto en móviles */
            }
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <!-- Navegación superior -->
    @include('web.partials.nav')

    <!-- Header opcional -->
    @if(View::hasSection('header'))
        @include('web.partials.header')
    @endif

    <!-- Contenido principal centrado -->
    <main class="app-main">
        @yield('contenido')
    </main>

    <!-- Footer -->
    @include('web.partials.footer')

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/adminlte.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar-wrapper');
            if (sidebar && window.OverlayScrollbarsGlobal?.OverlayScrollbars) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebar, {
                    scrollbars: { theme: 'os-theme-light', autoHide: 'leave', clickScroll: true },
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
