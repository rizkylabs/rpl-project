<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduSync')</title>
    @include('components.edusync.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="es-app" data-page="@yield('page', 'login')">
    <main id="main-content" class="es-auth-shell">
        @yield('content')
    </main>
</body>
</html>
