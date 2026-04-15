{{-- layouts/dashboard.blade.php — SIDEBAR MISE À JOUR --}}
{{-- Remplacer le bloc <nav class="sidebar-nav"> par ceci : --}}

<nav class="sidebar-nav">
    <a href="{{ route('dashboard.index') }}" class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
        <i class="fas fa-home s-icon"></i> Tableau de bord
    </a>

    <a href="{{ route('dashboard.articles') }}" class="sidebar-link {{ request()->routeIs('dashboard.articles') ? 'active' : '' }}">
        <i class="fas fa-newspaper s-icon"></i> Articles
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

    {{-- ✅ NOUVEAU : Gestion des utilisateurs --}}
    <a href="{{ route('dashboard.users') }}" class="sidebar-link {{ request()->routeIs('dashboard.users') ? 'active' : '' }}">
        <i class="fas fa-users s-icon"></i> Utilisateurs
    </a>
</nav>