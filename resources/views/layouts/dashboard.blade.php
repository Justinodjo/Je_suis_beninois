<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Je Suis Béninois - Dashboard Administrateur">

    <title>@yield('title', 'Tableau de bord - Je Suis Béninois')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <!-- CSS Dashboard -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-flag">
                <div class="fv"></div>
                <div class="fy"></div>
                <div class="fr"></div>
            </div>
            <div class="sidebar-brand-text">
                JE SUIS BÉNINOIS
                <small>ADMIN</small>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-label">Menu</div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard.index') }}" class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <i class="fas fa-home s-icon"></i> Tableau de bord
                </a>
               
                <a href="{{ route('dashboard.categories') }}" class="sidebar-link {{ request()->routeIs('dashboard.categories') ? 'active' : '' }}">
                    <i class="fas fa-layer-group s-icon"></i> Catégories
                </a>
              
                <a href="{{ route('dashboard.media') }}" class="sidebar-link {{ request()->routeIs('dashboard.media') ? 'active' : '' }}">
                    <i class="fas fa-photo-video s-icon"></i> Médias
                </a>
                
                <a href="{{ route('dashboard.stats') }}" class="sidebar-link {{ request()->routeIs('dashboard.stats') ? 'active' : '' }}">
                    <i class="fas fa-chart-line s-icon"></i> Statistiques
                </a>
            </nav>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                    @else
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    @endif
                </div>
                <div>
                    <div class="su-name">{{ auth()->user()->name }}</div>
                    <div class="su-role">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <button class="sf-btn sf-logout" onclick="document.getElementById('logout-form').submit()">
                <i class="fas fa-right-from-bracket"></i> Déconnexion
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="d-main">
        <!-- TOPBAR -->
        <header class="topbar">
            <button class="burger-btn" onclick="document.querySelector('.sidebar').classList.toggle('open'); document.querySelector('.sidebar-overlay').classList.toggle('show')">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-title">@yield('title', 'Tableau de bord')</div>
            <div class="topbar-crumbs">
                <a href="{{ route('dashboard.index') }}">Dashboard</a> <span class="sep">/</span> @yield('crumb')
            </div>
            <div class="topbar-actions">
                <!-- Exemple bouton notification -->
                <button class="t-btn notif-btn"><i class="fas fa-bell"></i></button>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="page-content">
            @yield('content')
        </main>
    </div>

    <!-- Overlay pour mobile -->
    <div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('open'); this.classList.remove('show')"></div>

    <!-- Configuration JS globale -->
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            apiUrl: '{{ config("app.url") }}/api/v1',
            user: @json(auth()->user())
        };
    </script>

    <!-- JS Dashboard -->
    <!-- <script src="{{ asset('js/modules/state.js') }}"></script>
    <script src="{{ asset('js/modules/api.js') }}"></script>
    <script src="{{ asset('js/modules/auth.js') }}"></script>
    <script src="{{ asset('js/modules/articles.js') }}"></script>
    <script src="{{ asset('js/modules/media.js') }}"></script>
    <script src="{{ asset('js/modules/categories.js') }}"></script>
    <script src="{{ asset('js/modules/tags.js') }}"></script>
    <script src="{{ asset('js/modules/app.js') }}"></script>
    <script src="{{ asset('js/modules/init.js') }}"></script> -->
    @stack('scripts')
</body>
</html>