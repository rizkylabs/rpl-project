<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Dashboard') - EduSync</title>
    @include('components.edusync.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="es-app" data-page="@yield('page', 'portal')">
    <a class="es-skip" href="#main-content">Lewati ke konten</a>
    <div class="es-shell">
        @include('components.edusync.navbar')
        <main id="main-content" class="es-main" tabindex="-1">
            @yield('content')
            <footer class="es-portal-footer">EduSync <span>Portal {{ $portalLabel }}</span></footer>
        </main>
    </div>
</body>
</html>
