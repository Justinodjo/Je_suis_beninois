@extends('layouts.auth')

@section('title', 'Inscription - Je Suis Béninois')

@section('content')

<div class="auth-logo">
    <a href="{{ route('home') }}">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Je Suis Béninois">
    </a>
    <h1>Créer un compte</h1>
    <p>Rejoignez la communauté Je Suis Béninois</p>
</div>

<div id="registerError" class="error-message"></div>
<div id="registerSuccess" class="success-message"></div>

<form id="registerForm">

    <div class="form-group">
        <label for="name">Nom complet</label>
        <input type="text" id="name" required autocomplete="name">
    </div>

    <div class="form-group">
        <label for="email">Adresse email</label>
        <input type="email" id="email" required autocomplete="email">
    </div>

    <div class="form-group" style="position:relative;">
        <label for="password">Mot de passe</label>
        <input
            type="password"
            id="password"
            required
            autocomplete="new-password"
            oninput="checkPasswordStrength(this.value)"
            onfocus="showPasswordTooltip()"
            onblur="hidePasswordTooltip()"
        >

        {{-- ══ TOOLTIP DE COMPOSITION DU MOT DE PASSE ══ --}}
        <div id="passwordTooltip" class="password-tooltip">
            <div class="pt-title">Le mot de passe doit contenir :</div>
            <ul class="pt-rules">
                <li id="rule-length"><i class="fa-solid fa-circle-xmark"></i> Au moins 8 caractères</li>
                <li id="rule-upper"><i class="fa-solid fa-circle-xmark"></i> Une lettre majuscule</li>
                <li id="rule-lower"><i class="fa-solid fa-circle-xmark"></i> Une lettre minuscule</li>
                <li id="rule-number"><i class="fa-solid fa-circle-xmark"></i> Un chiffre</li>
                <li id="rule-special"><i class="fa-solid fa-circle-xmark"></i> Un caractère spécial (!@#$%...)</li>
            </ul>
        </div>
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" required autocomplete="new-password">
    </div>

    <button type="submit" class="btn-submit" id="registerButton">
        S'inscrire
    </button>

</form>

<div class="auth-footer">
    Vous avez déjà un compte ?
    <a href="{{ route('login') }}">Se connecter</a>
</div>

@endsection

@push('styles')
<style>
.password-tooltip {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    margin-top: 6px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
    padding: 14px 16px;
    z-index: 10;
}
.password-tooltip.show { display: block; }

.pt-title {
    font-size: .78rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
}
.pt-rules {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.pt-rules li {
    font-size: .78rem;
    color: var(--text-l);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color .15s;
}
.pt-rules li i {
    color: var(--rouge);
    font-size: .75rem;
    transition: color .15s;
}
.pt-rules li.valid {
    color: var(--vert);
}
.pt-rules li.valid i {
    color: var(--vert);
}
.pt-rules li.valid i::before {
    content: "\f00c"; /* fa-circle-check */
}
</style>
@endpush

@push('scripts')
<script>

/*
|--------------------------------------------------------------------------
| Règles de composition du mot de passe
|--------------------------------------------------------------------------
*/

const PASSWORD_RULES = {
    length:  pwd => pwd.length >= 8,
    upper:   pwd => /[A-Z]/.test(pwd),
    lower:   pwd => /[a-z]/.test(pwd),
    number:  pwd => /[0-9]/.test(pwd),
    special: pwd => /[^A-Za-z0-9]/.test(pwd),
};

function checkPasswordStrength(pwd) {
    let allValid = true;

    Object.entries(PASSWORD_RULES).forEach(([rule, test]) => {
        const li = document.getElementById('rule-' + rule);
        const valid = test(pwd);
        li.classList.toggle('valid', valid);
        if (!valid) allValid = false;
    });

    return allValid;
}

function isPasswordValid(pwd) {
    return Object.values(PASSWORD_RULES).every(test => test(pwd));
}

function showPasswordTooltip() {
    document.getElementById('passwordTooltip').classList.add('show');
}

function hidePasswordTooltip() {
    // Petit délai pour laisser le temps au clic sur le tooltip si besoin
    setTimeout(() => {
        document.getElementById('passwordTooltip').classList.remove('show');
    }, 150);
}


/*
|--------------------------------------------------------------------------
| Soumission du formulaire
|--------------------------------------------------------------------------
*/

const registerForm = document.getElementById('registerForm');

registerForm.addEventListener('submit', async function(event) {
    event.preventDefault();

    const name                  = document.getElementById('name').value;
    const email                 = document.getElementById('email').value;
    const password              = document.getElementById('password').value;
    const password_confirmation = document.getElementById('password_confirmation').value;

    const errorDiv   = document.getElementById('registerError');
    const successDiv = document.getElementById('registerSuccess');
    const button     = document.getElementById('registerButton');

    errorDiv.style.display   = 'none';
    successDiv.style.display = 'none';

    // ── Vérification composition du mot de passe ──
    if (!isPasswordValid(password)) {
        showPasswordTooltip();
        errorDiv.textContent = 'Le mot de passe ne respecte pas les critères requis.';
        errorDiv.style.display = 'block';
        return;
    }

    // ── Vérification correspondance ──
    if (password !== password_confirmation) {
        errorDiv.textContent = 'Les mots de passe ne correspondent pas.';
        errorDiv.style.display = 'block';
        return;
    }

    button.disabled  = true;
    button.innerHTML = 'Création du compte...';

    try {
        const response = await fetch('/api/v1/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name,
                email,
                password,
                password_confirmation,
                role: 'visiteur',
                statut: 'actif'
            })
        });

        const data = await response.json();

        if (!response.ok) {
            let message = data.message || 'Erreur lors de l\'inscription';

            if (data.errors) {
                const firstError = Object.values(data.errors)[0];
                if (firstError) message = firstError[0];
            }

            errorDiv.textContent   = message;
            errorDiv.style.display = 'block';
            return;
        }

        successDiv.textContent   = 'Compte créé avec succès. Un email de bienvenue vous a été envoyé. Redirection...';
        successDiv.style.display = 'block';

        if (data.access_token) {
            localStorage.setItem('auth_token', data.access_token);
        }

        setTimeout(() => {
            window.location.href = '{{ route('login') }}';
        }, 1800);

    } catch (error) {
        console.error(error);
        errorDiv.textContent   = 'Impossible de contacter le serveur.';
        errorDiv.style.display = 'block';
    } finally {
        button.disabled  = false;
        button.innerHTML = 'S\'inscrire';
    }
});

</script>
@endpush