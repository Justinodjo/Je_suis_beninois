/**
 * Header : état de connexion rendu côté serveur (cookie JWT HttpOnly).
 * Gère les boutons « Déconnexion » (barre du haut et menu mobile).
 */
(function () {
    if (window.__headerLogoutBound) return;   // un seul écouteur, même avec Turbo
    window.__headerLogoutBound = true;

    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-logout]')) return;

        fetch('/api/v1/logout', {
            method: 'POST',
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        }).finally(() => {
            localStorage.removeItem('auth_token');
            window.location.href = '/';
        });
    });
})();