<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Je Suis Béninois - Actualités, culture, traditions et patrimoine du Bénin.">

     <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    >
    <title>@yield('title', 'Dashboard - Je Suis Béninois')</title>
    
    <!-- Styles -->
    @vite(['resources/css/dashboard.css'])
    @stack('styles')
</head>
<body>
    @yield('content')
    
    <!-- Scripts Dashboard - Ordre IMPORTANT -->
    <script>
        // Configuration globale
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            apiUrl: '{{ config("app.url") }}/api/v1',
            user: @json(auth()->user())
        };
    </script>
    
    @vite([
        'resources/js/dashboard/state.js',
        'resources/js/dashboard/api.js',
        'resources/js/dashboard/auth.js',
        'resources/js/dashboard/articles.js',
        'resources/js/dashboard/media.js',
        'resources/js/dashboard/categories.js',
        'resources/js/dashboard/tags.js',
        'resources/js/dashboard/app.js',
        'resources/js/dashboard/init.js'
    ])
    
    @stack('scripts')
</body>
</html>