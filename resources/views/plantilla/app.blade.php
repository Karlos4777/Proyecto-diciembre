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

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Core theme CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/web.css?v=' . time()) }}" rel="stylesheet" />
    <link href="{{ asset('css/site-fixes.css?v=' . time()) }}" rel="stylesheet" />
    <link href="{{ asset('css/admin.css?v=' . time()) }}" rel="stylesheet" />
    @stack('estilos')
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
    <script>
        // Small global vars used by web.js (generated via Blade)
        window.__app_storage_url__ = "{{ asset('storage') }}";
        window.__app_default_img__ = "{{ asset('img/default.jpg') }}";
    </script>
    <script src="{{ asset('js/web.js') }}"></script>
    @stack('scripts')
</body>
</html>
