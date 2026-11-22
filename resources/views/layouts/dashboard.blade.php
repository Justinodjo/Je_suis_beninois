<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard - Je Suis Béninois')</title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    >

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="{{ asset('js/turbo.min.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}" defer></script>

    @stack('styles')
</head>

<body>

{{-- ══════════════════════════════════════════════════════════════
     OVERLAY MOBILE
══════════════════════════════════════════════════════════════ --}}
<div
    class="sidebar-overlay"
    id="sidebarOverlay"
    onclick="closeSidebar()"
></div>


{{-- ══════════════════════════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════════════════════════ --}}
<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">

        <div class="sidebar-flag">
            <img
                    src="{{ asset('images/logo.jpeg') }}"
                    alt="Je Suis Béninois"
                >
        </div>

        <div class="sidebar-brand-text">
            JE SUIS BÉNINOIS
            <small>Dashboard</small>
        </div>

    </div>


    {{-- Navigation --}}
    <div class="sidebar-section">

        <div class="sidebar-label">
            Navigation
        </div>

        <nav class="sidebar-nav">

            <a
                href="{{ route('dashboard.index') }}"
                class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}"
            >
                <span class="s-icon">
                    <i class="fa-solid fa-gauge-high"></i>
                </span>

                Vue d'ensemble
            </a>


            <a
                href="{{ route('dashboard.articles') }}"
                class="sidebar-link {{ request()->routeIs('dashboard.articles') ? 'active' : '' }}"
            >
                <span class="s-icon">
                    <i class="fa-solid fa-newspaper"></i>
                </span>

                Articles
            </a>


            <a
                href="{{ route('dashboard.categories') }}"
                class="sidebar-link {{ request()->routeIs('dashboard.categories') ? 'active' : '' }}"
            >
                <span class="s-icon">
                    <i class="fa-solid fa-tags"></i>
                </span>

                Catégories & Tags
            </a>


            <a
                href="{{ route('dashboard.media') }}"
                class="sidebar-link {{ request()->routeIs('dashboard.media') ? 'active' : '' }}"
            >
                <span class="s-icon">
                    <i class="fa-solid fa-photo-film"></i>
                </span>

                Médiathèque
            </a>


            <a
                href="{{ route('dashboard.stats') }}"
                class="sidebar-link {{ request()->routeIs('dashboard.stats') ? 'active' : '' }}"
            >
                <span class="s-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </span>

                Statistiques
            </a>

              <a
                href="{{ route('dashboard.users') }}"
                class="sidebar-link {{ request()->routeIs('dashboard.users') ? 'active' : '' }}"
            >
                <span class="s-icon">
                    <i class="fa-solid fa-users"></i>
                </span>

                Utilisateurs
            </a>
        </nav>

    </div>


    <div class="sidebar-divider"></div>


    {{-- ══════════════════════════════════════════════════════════
         UTILISATEUR
    ═══════════════════════════════════════════════════════════ --}}

    <div class="sidebar-footer">

        <div class="sidebar-user">

            <div
                class="sidebar-avatar"
                id="sidebarAvatar"
            >
                A
            </div>

            <div>

                <div
                    class="su-name"
                    id="sidebarUserName"
                >
                    Chargement...
                </div>

                <div
                    class="su-role"
                    id="sidebarUserRole"
                >
                    ...
                </div>

            </div>

        </div>


        {{-- Boutons --}}
        <div class="sf-btns">

            <a
                href="{{ route('home') }}"
                class="sf-btn sf-site"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Site
            </a>


            {{-- Logout JWT --}}
            <button
                type="button"
                class="sf-btn sf-logout"
                style="width:100%;"
                onclick="logout()"
            >
                <i class="fa-solid fa-right-from-bracket"></i>
                Déconnexion
            </button>

        </div>

    </div>

</aside>


{{-- ══════════════════════════════════════════════════════════════
     MAIN
══════════════════════════════════════════════════════════════ --}}
<div class="d-main">


    {{-- Topbar --}}
    <div class="topbar">

        <button
            class="burger-btn"
            onclick="toggleSidebar()"
        >
            <i class="fa-solid fa-bars"></i>
        </button>


        <div>

            <div class="topbar-title">
                @yield('page_title', 'Dashboard')
            </div>

            <div class="topbar-crumbs">

                <a href="{{ route('dashboard.index') }}">
                    Dashboard
                </a>

                @yield('breadcrumb')

            </div>

        </div>


        <div class="topbar-actions">

            @yield('topbar_actions')

            <button
                class="t-btn notif-btn"
                title="Notifications"
            >
                <i class="fa-solid fa-bell"></i>
            </button>

        </div>

    </div>


    {{-- Contenu --}}
    <div class="page-content">

        @yield('content')

    </div>

</div>


{{-- ══════════════════════════════════════════════════════════════
     TOAST
══════════════════════════════════════════════════════════════ --}}
<div id="toastContainer"></div>


<script>

/*
|--------------------------------------------------------------------------
| CONFIGURATION JWT
|--------------------------------------------------------------------------
*/
function esc(v) {
    return String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

const API_BASE_URL = '/api/v1';


/*
|--------------------------------------------------------------------------
| Récupérer le token JWT
|--------------------------------------------------------------------------
*/

function getAuthToken() {

       return null; // le JWT est dans un cookie HttpOnly : illisible par le JavaScript

}


/*
|--------------------------------------------------------------------------
| apiFetch()
|--------------------------------------------------------------------------
|
| Toutes les requêtes API protégées passent par cette fonction.
|
| Elle ajoute automatiquement :
|
| Authorization: Bearer TOKEN
|
|--------------------------------------------------------------------------
*/

async function apiFetch(url, options = {}, _retried = false) {
 
    const isFormData = options.body instanceof FormData;


    const headers = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest', // exigé par l'anti-CSRF du cookie JWT
        ...(options.headers || {})
    };


 

    if (!isFormData && options.body) {

        headers['Content-Type'] = 'application/json';

    }



    const response = await fetch(url, {

        ...options,

        headers,
        credentials: 'same-origin' // le cookie HttpOnly part tout seul

    });


    if (
        response.status === 401 &&
        !url.includes('/login') &&
        !url.includes('/refresh')
    ) {
        // JWT expiré : renouvellement via le cookie, puis on rejoue la requête une fois
        if (!_retried && await refreshSession()) {
            return apiFetch(url, options, true);
        }
        window.location.href = '/login';

    }
    return response;
}
let _refreshing = null;
 
function refreshSession() {
    _refreshing = _refreshing || fetch(API_BASE_URL + '/refresh', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
        .then(r => r.ok)
        .catch(() => false)
        .finally(() => { _refreshing = null; });

    return _refreshing;
    

}


/*
|--------------------------------------------------------------------------
| Vérification de l'authentification
|--------------------------------------------------------------------------
|
| GET /api/v1/me
|
|--------------------------------------------------------------------------
*/

async function checkAuthentication() {






    try {

        const response =
            await apiFetch(
                API_BASE_URL + '/me'
            );


        /*
        |--------------------------------------------------------------------------
        | Token invalide
        |--------------------------------------------------------------------------
        */

        if (!response.ok) {

            localStorage.removeItem(
                'auth_token'
            );

            window.location.href = '/';

            return;

        }


        const data =
            await response.json();


        const user =
            data.user;


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'utilisateur
        |--------------------------------------------------------------------------
        */

        if (!user) {

            localStorage.removeItem(
                'auth_token'
            );

            window.location.href = '/';

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Vérification du rôle
        |--------------------------------------------------------------------------
        */

        if (user.role !== 'admin') {

            console.warn(
                'Accès dashboard refusé.'
            );

            window.location.href = '/';

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Afficher utilisateur
        |--------------------------------------------------------------------------
        */

        const nameElement =
            document.getElementById(
                'sidebarUserName'
            );

        const roleElement =
            document.getElementById(
                'sidebarUserRole'
            );

        const avatarElement =
            document.getElementById(
                'sidebarAvatar'
            );


        if (nameElement) {

            nameElement.textContent =
                user.name || 'Admin';

        }


        if (roleElement) {

            roleElement.textContent =
                user.role || 'admin';

        }


        if (avatarElement) {

            avatarElement.textContent =
                user.name
                    ? user.name
                        .charAt(0)
                        .toUpperCase()
                    : 'A';

        }

    } catch (error) {

        console.error(
            'Erreur vérification JWT:',
            error
        );


        localStorage.removeItem(
            'auth_token'
        );


        window.location.href = '/';

    }

}


/*
|--------------------------------------------------------------------------
| Déconnexion JWT
|--------------------------------------------------------------------------
*/

async function logout() {
    try {
        await fetch(API_BASE_URL + '/logout', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        });
    } catch (error) {
        console.error('Erreur déconnexion:', error);
    } finally {
        localStorage.removeItem('auth_token'); // nettoyage de l'ancien stockage
        window.location.href = '/login';       // ou '/' pour l'accueil
    }
}


/*
|--------------------------------------------------------------------------
| Toast global
|--------------------------------------------------------------------------
*/

function showToast(
    message,
    type = 'success'
) {

    const container =
        document.getElementById(
            'toastContainer'
        );


    const toast =
        document.createElement('div');


    toast.className =
        `toast toast-${type}`;


    const icons = {

        success:
            'fa-circle-check',

        error:
            'fa-circle-xmark',

        info:
            'fa-circle-info'

    };


    toast.innerHTML =
        `<i class="fa-solid ${
            icons[type] || icons.success
        }"></i> ${esc(message)}`;


    container.appendChild(
        toast
    );


    setTimeout(
        () => toast.classList.add('show'),
        10
    );


    setTimeout(() => {

        toast.classList.remove(
            'show'
        );


        setTimeout(
            () => toast.remove(),
            300
        );

    }, 3500);

}


/*
|--------------------------------------------------------------------------
| Suppression
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Toast de confirmation (remplace confirm() natif)
|--------------------------------------------------------------------------
|
| Affiche un toast avec deux boutons : Confirmer / Annuler.
| Le toast reste affiché tant que l'utilisateur n'a pas choisi.
|
*/

function showConfirmToast(message, onConfirm) {

    const container =
        document.getElementById('toastContainer');

    const toast =
        document.createElement('div');

    toast.className = 'toast toast-confirm';

    toast.innerHTML = `
        <div style="display:flex;align-items:center;gap:12px;">
            <i class="fa-solid fa-triangle-exclamation" style="color:#fb923c;"></i>
            <span>${esc(message)}</span>
            <div style="display:flex;gap:6px;margin-left:auto;">
                <button class="toast-btn toast-btn-cancel">
                    Annuler
                </button>
                <button class="toast-btn toast-btn-confirm">
                    <i class="fa-solid fa-trash"></i> Supprimer
                </button>
            </div>
        </div>
    `;

    container.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 10);

    function dismiss() {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }

    toast.querySelector('.toast-btn-cancel')
        .addEventListener('click', dismiss);

    toast.querySelector('.toast-btn-confirm')
        .addEventListener('click', () => {
            dismiss();
            onConfirm();
        });

    // Auto-dismiss après 8s si aucune action
    setTimeout(() => {
        if (document.body.contains(toast)) dismiss();
    }, 8000);
}


/*
|--------------------------------------------------------------------------
| Suppression (via toast de confirmation)
|--------------------------------------------------------------------------
*/

function confirmDelete(url, nom) {

    showConfirmToast(
        `Supprimer "${nom}" ?`,
        async () => {

            try {

                const response =
                    await apiFetch(url, { method: 'DELETE' });

                if (response.ok) {

                    showToast(`"${nom}" supprimé ✓`);

                    setTimeout(
                        () => window.location.reload(),
                        800
                    );

                } else {

                    let data = {};

                    try {
                        data = await response.json();
                    } catch (e) {}

                    showToast(
                        data.message || 'Erreur lors de la suppression',
                        'error'
                    );
                }

            } catch (error) {

                console.error(error);

                showToast('Erreur réseau', 'error');
            }
        }
    );
}


/*
|--------------------------------------------------------------------------
| Sidebar
|--------------------------------------------------------------------------
*/

function toggleSidebar() {

    document
        .getElementById('sidebar')
        .classList
        .toggle('open');


    document
        .getElementById('sidebarOverlay')
        .classList
        .toggle('show');

}


function closeSidebar() {

    document
        .getElementById('sidebar')
        .classList
        .remove('open');


    document
        .getElementById('sidebarOverlay')
        .classList
        .remove('show');

}


/*
|--------------------------------------------------------------------------
| Initialisation
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'turbo:load',
    function () {
        checkAuthentication();
        closeSidebar(); // referme la sidebar mobile si restée ouverte entre deux pages
    }
);

</script>


@stack('scripts')

</body>
</html>
