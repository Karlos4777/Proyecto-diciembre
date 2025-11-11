<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="ArtCode" />
    <meta name="description" content="Shop | ArtCode.com" />
    <meta name="keywords" content="Shop, ArtCode" />
    <title>@yield('titulo', 'Shop - DisZone')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />

    <!-- Bootstrap icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Core theme CSS -->
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
            background-color: #f8f9fa;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- Navegación -->
    @include('web.partials.nav')

    <!-- Header opcional -->
    @if(View::hasSection('header'))
        @include('web.partials.header')
    @endif

    <!-- Contenido principal -->
    <main>
        @yield('contenido')
    </main>

    <!-- Footer -->
    @include('web.partials.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    @stack('scripts')
</body>
</html>
