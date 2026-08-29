{{-- ═══════════════════════════════════════════
     PARTIAL — HEADER
     Inclus dans: layouts/app.blade.php
═══════════════════════════════════════════ --}}
<header class="site-header">
    <div class="container">
        <div class="header-inner">

            <a href="{{ route('home') }}" class="navbar-brand">
             <div class="logo-flag">
    <img src="{{ asset('images/logo.jpeg') }}"
         alt="Je Suis Béninois"
         class="site-logo">
         </div>
</a>

            {{-- Overlay mobile --}}
            <div class="nav-mobile-overlay" id="navMobileOverlay" onclick="closeMobileNav()"></div>

            <nav class="main-nav" id="mainNav">
                <button class="btn-icon nav-mobile-close" onclick="closeMobileNav()" style="align-self:flex-end;margin-bottom:12px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
                <a href="{{ route('actualites') }}" class="{{ request()->routeIs('actualites') ? 'active' : '' }}">Actualités</a>
                <a href="{{ route('culture.index') }}" class="{{ request()->routeIs('culture.index') ? 'active' : '' }}">Culture</a>
                <a href="{{ route('culture.patrimoine') }}" class="{{ request()->routeIs('culture.patrimoine') ? 'active' : '' }}">Histoire</a>
                <a href="#galerie">Galerie</a>
                <a href="{{ route('interviews.index') }}" class="{{ request()->routeIs('interviews.*') ? 'active' : '' }}">Interviews</a>
                <a href="#contact">Contact</a>
            </nav>

            <div class="header-actions">

    <a href="#" class="btn-icon">
        <svg viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
        </svg>
    </a>

    @auth

        <a
            href="{{ route('dashboard.index') }}"
            class="btn btn-primary"
            style="font-size:.82rem;padding:8px 18px;"
        >
            <i class="fa-solid fa-gauge-high"></i>
            Dashboard
        </a>

    @else

        <a
            href="{{ route('login') }}"
            class="btn btn-login"
        >
            Se connecter
        </a>

        <a
            href="{{ route('register') }}"
            class="btn btn-secondary"
        >
            S'inscrire
        </a>

    @endauth

    <button class="nav-burger" onclick="openMobileNav()">
        <i class="fa-solid fa-bars"></i>
    </button>

</div>
        </div>
    </div>
</header>

@push('scripts')
<script>
// ── MENU MOBILE ──
function openMobileNav() {
    document.getElementById('mainNav').classList.add('open');
    document.getElementById('navMobileOverlay').classList.add('show');
}

function closeMobileNav() {
    document.getElementById('mainNav').classList.remove('open');
    document.getElementById('navMobileOverlay').classList.remove('show');
}
</script>
@endpush