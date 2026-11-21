/**
 * État de connexion du header (auth JWT).
 * Le serveur ne connaît pas l'utilisateur (pas de session) : on lit le token
 * en localStorage, on le valide via GET /api/v1/me, puis on bascule l'affichage.
 */
(function () {
    const guest = document.getElementById('header-guest');
    const box = document.getElementById('header-user');
    if (!guest || !box) return;

    const token = localStorage.getItem('auth_token');
    if (!token || token === 'undefined' || token === 'null') return; // reste en mode visiteur

    // Évite le clignotement : on masque le bloc visiteur dès qu'un token existe
    guest.style.display = 'none';

    const clearSession = () => localStorage.removeItem('auth_token');
    const showGuest = () => { guest.style.display = 'contents'; box.style.display = 'none'; };

    fetch('/api/v1/me', {
        headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' },
    })
        .then((res) => {
            if (!res.ok) throw new Error(res.status); // 401 : token expiré ou invalide
            return res.json();
        })
        .then(({ user }) => {
            if (!user || user.statut !== 'actif') throw new Error('inactif');

            document.getElementById('header-username').textContent = user.name; // textContent : pas de XSS
            if (user.role === 'admin' || user.role === 'contributeur') {
                document.getElementById('header-dashboard').style.display = '';
            }
            box.style.display = 'flex';
        })
        .catch((err) => {
            // Token rejeté : on nettoie. Erreur réseau/5xx : on garde le token, on affiche juste le mode visiteur.
            if (['401', '403', 'inactif'].includes(String(err.message))) clearSession();
            showGuest();
        });

    document.getElementById('header-logout').addEventListener('click', () => {
        fetch('/api/v1/logout', {
            method: 'POST',
            headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' },
        }).finally(() => {
            clearSession();
            window.location.href = '/';
        });
    });
})();