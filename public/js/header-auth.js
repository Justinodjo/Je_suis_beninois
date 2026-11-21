/**
 * Header : l'état de connexion est rendu côté serveur (cookie JWT HttpOnly).
 * Ce script ne gère plus que le bouton « Déconnexion ».
 */
(function () {
    const btn = document.getElementById('header-logout');
    if (!btn) return;

    btn.addEventListener('click', () => {
        fetch('/api/v1/logout', {
            method: 'POST',
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        }).finally(() => {
            localStorage.removeItem('auth_token'); // nettoyage de l'ancien stockage
            window.location.href = '/';
        });
    });
})();