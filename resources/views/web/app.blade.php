<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Shop | ArtCode.com" />
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
</head>
<body>
    <!--  Navegación -->
    @include('web.partials.nav')

    <!--  Header (solo si la vista lo define) -->
    @if(View::hasSection('header'))
        @include('web.partials.header')
    @endif

    <!--  Sección de búsqueda / contenido dinámico -->
    @yield('contenido')

    <!--  Footer -->
    @include('web.partials.footer')

    <!-- JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts personalizados -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    @stack('scripts')
</body>
</html>
