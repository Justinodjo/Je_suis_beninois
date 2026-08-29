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
                <a href="{{ route('galerie') }}" class="{{ request()->routeIs('galerie') ? 'active' : '' }}">Galerie</a>
                <a href="{{ route('interviews.index') }}" class="{{ request()->routeIs('interviews.*') ? 'active' : '' }}">Interviews</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            </nav>

            <div class="header-actions">

                <button type="button" class="btn-icon" onclick="openSearchModal()" aria-label="Rechercher">
                    <svg viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                </button>

                @auth
                    <a href="{{ route('dashboard.index') }}" class="btn btn-primary" style="font-size:.82rem;padding:8px 18px;">
                        <i class="fa-solid fa-gauge-high"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">S'inscrire</a>
                @endauth

                <button class="nav-burger" onclick="openMobileNav()">
                    <i class="fa-solid fa-bars"></i>
                </button>

            </div>
        </div>
    </div>
</header>

{{-- ═══ MODAL RECHERCHE ═══ --}}
<div id="searchModal" class="search-modal">
    <div class="search-modal-inner">
        <button type="button" class="search-modal-close" onclick="closeSearchModal()" aria-label="Fermer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <form action="{{ route('actualites') }}" method="GET" class="search-modal-form">
            <i class="fa-solid fa-magnifying-glass search-modal-icon"></i>
            <input
                type="text"
                name="search"
                id="searchModalInput"
                placeholder="Rechercher un article, une tradition, une interview..."
                autocomplete="off"
                required
            >
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
        <p class="search-modal-hint">Appuyez sur <kbd>Échap</kbd> pour fermer</p>
    </div>
</div>

@push('styles')
<style>
/* ═══ MODAL RECHERCHE ═══ */
.search-modal {
    display: none;
    position: fixed; inset: 0;
    z-index: 300;
    background: rgba(15,20,15,.72);
    backdrop-filter: blur(2px);
    align-items: flex-start;
    justify-content: center;
    padding: 12vh 20px 0;
}
.search-modal.open { display: flex; }

.search-modal-inner {
    background: #fff;
    border-radius: 14px;
    width: 100%;
    max-width: 560px;
    padding: 28px 28px 20px;
    position: relative;
    box-shadow: 0 24px 64px rgba(0,0,0,.35);
    animation: searchModalIn .18s ease-out;
}
@keyframes searchModalIn {
    from { transform: translateY(-12px); opacity: 0; }
    to   { transform: translateY(0); opacity: 1; }
}

.search-modal-close {
    position: absolute; top: 14px; right: 14px;
    width: 32px; height: 32px;
    border-radius: 50%;
    border: none; background: var(--gris-c);
    color: var(--text-l);
    font-size: 1rem;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s;
}
.search-modal-close:hover { background: var(--border); }

.search-modal-form {
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 6px 6px 6px 16px;
    margin-top: 10px;
}
.search-modal-form:focus-within {
    border-color: var(--vert);
    box-shadow: 0 0 0 3px rgba(27,94,32,.12);
}
.search-modal-icon { color: var(--gris-t); font-size: .95rem; }
.search-modal-form input {
    flex: 1;
    border: none; outline: none;
    font-size: .92rem;
    font-family: var(--font-body);
    padding: 10px 0;
    background: transparent;
}
.search-modal-form .btn { flex-shrink: 0; }

.search-modal-hint {
    font-size: .74rem;
    color: var(--gris-t);
    margin-top: 12px;
}
.search-modal-hint kbd {
    background: var(--gris-c);
    border: 1px solid var(--border);
    border-radius: 4px;
    padding: 1px 6px;
    font-family: inherit;
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .search-modal { padding: 0; align-items: stretch; }
    .search-modal-inner {
        max-width: none;
        border-radius: 0;
        height: 100vh;
        padding: 20px 18px;
        display: flex;
        flex-direction: column;
        animation: none;
    }
    .search-modal-form { margin-top: 24px; flex-wrap: wrap; padding: 6px 10px 6px 16px; }
    .search-modal-form input { width: 100%; }
    .search-modal-form .btn { width: 100%; justify-content: center; margin-top: 8px; }
    .search-modal-hint { display: none; }
}
</style>
@endpush

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

// ── MODAL RECHERCHE ──
function openSearchModal() {
    document.getElementById('searchModal').classList.add('open');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('searchModalInput')?.focus(), 50);
}

function closeSearchModal() {
    document.getElementById('searchModal').classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeSearchModal();
});

document.getElementById('searchModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeSearchModal();
});

// Réinitialiser à chaque navigation Turbo (menu + modal restent fermés)
document.addEventListener('turbo:load', function () {
    closeMobileNav();
    closeSearchModal();
});
</script>
@endpush