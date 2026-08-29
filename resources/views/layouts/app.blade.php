<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Je Suis Béninois')</title>
 <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    >

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Lora:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="{{ asset('js/turbo.min.js') }}"></script>
    <style>
    /* ═══════════════════════════════════════════
       VARIABLES GLOBALES — COULEURS BÉNIN
    ═══════════════════════════════════════════ */
    :root {
        --vert:      #1B5E20;
        --vert-m:    #2E7D32;
        --vert-l:    #388E3C;
        --jaune:     #FFD700;
        --jaune-d:   #F9A825;
        --rouge:     #C62828;
        --blanc:     #FAFAF7;
        --gris-c:    #F5F5F0;
        --gris-t:    #888;
        --text:      #1a1a1a;
        --text-l:    #555;
        --border:    #e0ddd6;
        --shadow:    0 4px 24px rgba(0,0,0,0.10);
        --radius:    10px;
        --font-titre: 'Playfair Display', Georgia, serif;
        --font-body:  'DM Sans', sans-serif;
        --font-serif: 'Lora', serif;
    }

    /* ═══════════════════════════════════════════
       RESET & BASE
    ═══════════════════════════════════════════ */
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { font-family: var(--font-body); color: var(--text); background: var(--blanc); line-height: 1.6; }
    img { max-width: 100%; height: auto; display: block; object-fit: cover; }
    a { text-decoration: none; color: inherit; }
    .container { max-width: 1180px; margin: 0 auto; padding: 0 24px; }

    /* ═══════════════════════════════════════════
       PATTERNS DÉCORATIFS AFRICAINS
    ═══════════════════════════════════════════ */
    .pattern-strip {
        height: 8px;
        background: repeating-linear-gradient(
            90deg,
            var(--vert) 0px, var(--vert) 20px,
            var(--jaune) 20px, var(--jaune) 40px,
            var(--rouge) 40px, var(--rouge) 60px
        );
    }
    .pattern-bg {
        background-image:
            radial-gradient(circle at 20% 50%, rgba(27,94,32,0.06) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255,215,0,0.06) 0%, transparent 50%),
            repeating-linear-gradient(45deg, transparent, transparent 40px, rgba(27,94,32,0.02) 40px, rgba(27,94,32,0.02) 80px);
    }

    /* ═══════════════════════════════════════════
       HEADER — FIDÈLE AUX MAQUETTES
    ═══════════════════════════════════════════ */
    .site-header {
        position: sticky; top: 0; z-index: 100;
        background: #fff;
        border-bottom: 1px solid var(--border);
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    }
    .header-inner {
        display: flex; align-items: center; gap: 40px;
        height: 72px;
    }
    .logo { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
    .logo-flag { display: flex; height: 48px; width: 32px; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
    .logo-flag .f-v { flex: 1; background: var(--vert); }
    .logo-flag .f-y { flex: 1; background: var(--jaune); }
    .logo-flag .f-r { flex: 1; background: var(--rouge); }
    .logo-text { font-family: var(--font-titre); font-size: 1.1rem; font-weight: 700; line-height: 1.2; color: var(--vert); }
    .logo-text span { display: block; font-size: 0.7rem; font-family: var(--font-body); font-weight: 600; color: var(--rouge); letter-spacing: .08em; text-transform: uppercase; }

    .main-nav { display: flex; align-items: center; gap: 4px; flex: 1; }
    .main-nav a {
        padding: 6px 14px; font-size: 0.88rem; font-weight: 500;
        color: var(--text-l); border-radius: 6px;
        transition: all .2s; white-space: nowrap;
        position: relative;
    }
    .main-nav a:hover, .main-nav a.active { color: var(--vert); background: rgba(27,94,32,0.07); }

    .header-actions { display: flex; align-items: center; gap: 10px; margin-left: auto; }
    .btn-login {
        background: var(--vert); color: #fff;
        padding: 8px 20px; border-radius: 24px;
        font-size: 0.82rem; font-weight: 600;
        transition: background .2s;
    }
    .btn-login:hover { background: var(--vert-l); }
    .btn-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-l); transition: background .2s; }
    .btn-icon:hover { background: var(--gris-c); }
    .btn-icon svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }

    /* ── Burger mobile ── */
    .nav-burger {
        display: none;
        width: 40px; height: 40px;
        border: none; background: none;
        align-items: center; justify-content: center;
        font-size: 1.3rem; color: var(--vert);
        cursor: pointer;
        border-radius: 8px;
        transition: background .2s;
    }
    .nav-burger:hover { background: var(--gris-c); }

    /* ── Overlay mobile ── */
    .nav-mobile-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 150;
    }
    .nav-mobile-overlay.show { display: block; }

    /* ═══════════════════════════════════════════
       BOUTONS GLOBAUX
    ═══════════════════════════════════════════ */
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border-radius: 8px; font-weight: 600; font-size: 0.88rem; transition: all .2s; cursor: pointer; border: none; }
    .btn-primary { background: var(--vert); color: #fff; }
    .btn-primary:hover { background: var(--vert-l); transform: translateY(-1px); }
    .btn-secondary { background: transparent; color: var(--vert); border: 2px solid var(--vert); }
    .btn-secondary:hover { background: var(--vert); color: #fff; }
    .btn-jaune { background: var(--jaune); color: #1a1a1a; }
    .btn-jaune:hover { background: var(--jaune-d); }

    /* ═══════════════════════════════════════════
       BADGES / TAGS
    ═══════════════════════════════════════════ */
    .badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
    .badge-vert { background: var(--vert); color: #fff; }
    .badge-jaune { background: var(--jaune); color: #1a1a1a; }
    .badge-rouge { background: var(--rouge); color: #fff; }
    .badge-outline { background: transparent; border: 1px solid currentColor; }

    /* ═══════════════════════════════════════════
       FOOTER
    ═══════════════════════════════════════════ */
    .site-footer { background: var(--vert); color: rgba(255,255,255,0.85); padding: 60px 0 0; }
    .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr; gap: 40px; padding-bottom: 40px; }
    .footer-brand .logo-text { color: #fff; }
    .footer-brand .logo-text span { color: var(--jaune); }
    .footer-brand p { margin-top: 12px; font-size: 0.82rem; line-height: 1.7; color: rgba(255,255,255,0.6); }
    .footer-col h4 { font-size: 0.78rem; text-transform: uppercase; letter-spacing: .1em; color: var(--jaune); margin-bottom: 14px; }
    .footer-col a { display: block; font-size: 0.82rem; color: rgba(255,255,255,0.7); margin-bottom: 8px; transition: color .2s; }
    .footer-col a:hover { color: #fff; }
    .footer-bottom { border-top: 1px solid rgba(255,255,255,0.12); padding: 20px 0; display: flex; justify-content: space-between; align-items: center; font-size: 0.78rem; color: rgba(255,255,255,0.4); }

    /* ── Réseaux sociaux footer (définition unique, nettoyée) ── */
    .footer-socials {
        display: flex;
        gap: 14px;
        margin-top: 16px;
    }
    .footer-social {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        transition: transform .25s ease, background .25s ease;
    }
    .footer-social:hover { transform: translateY(-4px); }
    .footer-social:nth-child(1):hover { background: #1877f2; } /* Facebook */
    .footer-social:nth-child(2):hover { background: #000; }     /* X */
    .footer-social:nth-child(3):hover { background: #ff0000; }  /* YouTube */
    .footer-social:nth-child(4):hover {
        background: linear-gradient(45deg,#f9ce34,#ee2a7b,#6228d7);
    }

    /* ════════════════════════════════════════
   FORMULAIRES MODALE
════════════════════════════════════════ */
#authModal form {
    display: flex;
    flex-direction: column;
}

#authModal label {
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--text-l);
}

#authModal input {
    padding: 10px 14px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.88rem;
    color: var(--text);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    width: 100%;
}

#authModal input:focus {
    border-color: var(--vert);
    box-shadow: 0 0 6px rgba(27, 94, 32, 0.3);
}

#authModal button {
    margin-top: 8px;
}

#authModal a {
    color: var(--vert);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

#authModal a:hover {
    color: var(--vert-l);
}

    /* ═══════════════════════════════════════════
       MENU MOBILE (RESPONSIVE)
    ═══════════════════════════════════════════ */
    @media (max-width: 900px) {
        .nav-burger { display: flex; }

        .main-nav {
            position: fixed;
            top: 0; right: -280px;
            width: 260px;
            height: 100vh;
            background: #fff;
            flex-direction: column;
            align-items: stretch;
            gap: 0;
            padding: 80px 20px 20px;
            z-index: 200;
            transition: right .25s ease;
            box-shadow: -8px 0 24px rgba(0,0,0,.12);
        }
        .main-nav.open { right: 0; }
        .main-nav a {
            padding: 12px 14px;
            border-radius: 8px;
            font-size: .95rem;
        }

        .header-actions .btn-login,
        .header-actions .btn-secondary { display: none; }
    }
    @media (min-width: 901px) {
        .nav-mobile-close { display: none; }
    }

    </style>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
     <!-- @vite(['resources/css/app.css']) -->

    
</head>
<body>

<!-- Pattern décoratif top -->
<div class="pattern-strip"></div>

@include('layouts.partials.header')

<!-- ═══ CONTENU ═══ -->
<main>
    @yield('content')
</main>

@include('layouts.partials.footer')

<!-- ═══ MODAL AUTH ═══ -->
<div id="authModal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:40px;width:420px;max-width:90vw;position:relative;">
        <button onclick="document.getElementById('authModal').style.display='none'"
            style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:1.5rem;cursor:pointer;color:#888;">×</button>

        <!-- SECTION LOGIN -->
        <div id="loginSection">
            <div style="text-align:center;margin-bottom:24px;">
                <div class="logo" style="justify-content:center;margin-bottom:12px;">
                       <a href="{{ route('home') }}" class="navbar-brand">
             <div class="logo-flag">
    <img src="{{ asset('images/logo.jpeg') }}"
         alt="Je Suis Béninois"
         class="site-logo">
         </div>
</a>
                </div>
                <p style="color:var(--text-l);font-size:.9rem;">Connectez-vous pour contribuer</p>
            </div>
            <form id="loginForm">
                @csrf
                <div style="margin-bottom:16px;">
                    <label>Email</label>
                    <input type="email" id="loginEmail" placeholder="votre@email.com" required>
                </div>
                <div style="margin-bottom:20px;">
                    <label>Mot de passe</label>
                    <input type="password" id="loginPassword" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">Se connecter</button>
                <div style="margin-top:12px;text-align:center;font-size:.82rem;color:var(--text-l);">
                    Pas de compte ? <a href="#" onclick="toggleAuthSection()">S'inscrire</a>
                </div>
                <div id="loginError" style="color:var(--rouge);margin-top:8px;text-align:center;"></div>
            </form>
        </div>

        <!-- SECTION REGISTER -->
        <div id="registerSection" style="display:none;">
            <div style="text-align:center;margin-bottom:24px;">
                <div class="logo" style="justify-content:center;margin-bottom:12px;">
                      <a href="{{ route('home') }}" class="navbar-brand">
             <div class="logo-flag">
    <img src="{{ asset('images/logo.jpeg') }}"
         alt="Je Suis Béninois"
         class="site-logo">
         </div>
</a>
                </div>
                <p style="color:var(--text-l);font-size:.9rem;">Créez votre compte pour contribuer</p>
            </div>
            <form id="registerForm">
                @csrf
                <div style="margin-bottom:16px;">
                    <label>Nom</label>
                    <input type="text" id="registerName" placeholder="Votre nom complet" required>
                </div>
                <div style="margin-bottom:16px;">
                    <label>Email</label>
                    <input type="email" id="registerEmail" placeholder="votre@email.com" required>
                </div>
                <div style="margin-bottom:16px;">
                    <label>Mot de passe</label>
                    <input type="password" id="registerPassword" placeholder="••••••••" required>
                </div>
                <div style="margin-bottom:20px;">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" id="registerPasswordConfirmation" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">S'inscrire</button>
                <div style="margin-top:12px;text-align:center;font-size:.82rem;color:var(--text-l);">
                    Déjà un compte ? <a href="#" onclick="toggleAuthSection()">Se connecter</a>
                </div>
                <div id="registerError" style="color:var(--rouge);margin-top:8px;text-align:center;"></div>
            </form>
        </div>
    </div>
</div>
@if(session('showLoginModal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('authModal').style.display = 'flex';
        });
    </script>
@endif

@stack('styles')
<script>
function toggleAuthSection() {
    const login = document.getElementById('loginSection');
    const register = document.getElementById('registerSection');
    login.style.display = login.style.display === 'none' ? 'block' : 'none';
    register.style.display = register.style.display === 'none' ? 'block' : 'none';
}

const csrfToken = () => document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ── LOGIN ──
document.getElementById('loginForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const email    = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const errorDiv = document.getElementById('loginError');
    errorDiv.style.display = 'none';

    try {
       const res = await fetch('/api/v1/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({ email, password })
});

       const data = await res.json();

if (res.ok) {
    localStorage.setItem('auth_token', data.access_token);

    window.location.href = '/dashboard';
} else {
    errorDiv.style.display = 'block';
    errorDiv.textContent = data.message || 'Identifiants invalides';
}
    } catch (err) {
        errorDiv.style.display = 'block';
        errorDiv.textContent   = 'Erreur de connexion';
    }
});

// ── REGISTER ──
document.getElementById('registerForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const name                  = document.getElementById('registerName').value;
    const email                 = document.getElementById('registerEmail').value;
    const password              = document.getElementById('registerPassword').value;
    const password_confirmation = document.getElementById('registerPasswordConfirmation').value;
    const errorDiv              = document.getElementById('registerError');
    errorDiv.style.display = 'none';

    try {
        // 1. Créer le compte
        const res  = await fetch('/api/v1/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
            body: JSON.stringify({ name, email, password, password_confirmation, role: 'visiteur', statut: 'actif' })
        });
        const data = await res.json();

        if (!res.ok) {
            errorDiv.style.display = 'block';
            errorDiv.textContent   = data.errors
                ? Object.values(data.errors)[0][0]
                : (data.message || "Erreur lors de l'inscription");
            return;
        }

        // 2. Connexion avec session web
        const loginRes  = await fetch('/web/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
            body: JSON.stringify({ email, password })
        });
        const loginData = await loginRes.json();

        if (loginRes.ok) {
            if (loginData.token) localStorage.setItem('auth_token', loginData.token);
            window.location.href = loginData.redirect || '/';
        } else {
            errorDiv.style.display = 'block';
            errorDiv.textContent   = loginData.message || 'Erreur de connexion';
        }
    } catch (err) {
        errorDiv.style.display = 'block';
        errorDiv.textContent   = 'Erreur de connexion';
    }
});
</script>

@stack('scripts')
</body>
</html>