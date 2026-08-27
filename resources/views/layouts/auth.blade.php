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

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --vert: #1B5E20;
            --vert-l: #388E3C;
            --jaune: #FFD700;
            --rouge: #C62828;
            --blanc: #FAFAF7;
            --text: #1a1a1a;
            --text-l: #666;
            --border: #e0ddd6;

            --font-titre: 'Playfair Display', Georgia, serif;
            --font-body: 'DM Sans', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top left, rgba(27,94,32,.12), transparent 35%),
                radial-gradient(circle at bottom right, rgba(255,215,0,.12), transparent 35%),
                #f5f5f0;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }

        /* ── BANNIÈRE ── */
        .auth-banner {
            background: linear-gradient(135deg, rgba(27,94,32,.95), rgba(46,125,50,.95));
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .auth-banner::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border: 60px solid rgba(255,255,255,.05);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }
        .auth-brand { position: relative; z-index: 2; }
        .auth-brand-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .auth-brand-logo img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; background: white; }
        .auth-brand-title { font-family: var(--font-titre); font-size: 1.4rem; font-weight: 700; }
        .auth-brand-subtitle { font-size: .75rem; color: var(--jaune); text-transform: uppercase; letter-spacing: 2px; }
        .auth-banner-content { position: relative; z-index: 2; }
        .auth-banner-content h1 { font-family: var(--font-titre); font-size: 2.5rem; line-height: 1.2; margin-bottom: 20px; }
        .auth-banner-content p { color: rgba(255,255,255,.75); line-height: 1.7; }
        .auth-back-home { position: relative; z-index: 2; color: white; font-size: .9rem; opacity: .8; text-decoration: none; }
        .auth-back-home:hover { opacity: 1; }

        /* ── FORMULAIRE ── */
        .auth-form-container { padding: 60px; display: flex; align-items: center; }
        .auth-form { width: 100%; }

        .auth-logo { text-align: center; margin-bottom: 24px; }
        .auth-logo img { width: 64px; height: 64px; object-fit: cover; border-radius: 10px; margin: 0 auto 14px; }
        .auth-logo h1 { font-family: var(--font-titre); font-size: 1.8rem; color: var(--vert); margin-bottom: 6px; }
        .auth-logo p { color: var(--text-l); font-size: .9rem; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: 8px; color: var(--text); }
        .form-group input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: .95rem;
            outline: none;
            transition: .2s;
        }
        .form-group input:focus {
            border-color: var(--vert);
            box-shadow: 0 0 0 3px rgba(27,94,32,.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: var(--vert);
            color: white;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            margin-top: 6px;
        }
        .btn-submit:hover { background: var(--vert-l); }
        .btn-submit:disabled { opacity: .7; cursor: not-allowed; }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: .9rem;
            color: var(--text-l);
        }
        .auth-footer a { color: var(--vert); font-weight: 600; text-decoration: none; }
        .auth-footer a:hover { color: var(--vert-l); }

        .error-message, .success-message {
            display: none;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 8px;
            font-size: .85rem;
        }
        .error-message { background: #ffebee; color: var(--rouge); }
        .success-message { background: #e8f5e9; color: var(--vert); }

        @media(max-width: 768px) {
            .auth-container { grid-template-columns: 1fr; max-width: 500px; }
            .auth-banner { display: none; }
            .auth-form-container { padding: 40px 30px; }
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="auth-container">

    {{-- ══ BANNIÈRE ══ --}}
    <div class="auth-banner">

        <div class="auth-brand">
            <div class="auth-brand-logo">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Je Suis Béninois">
                <div>
                    <div class="auth-brand-title">JE SUIS BÉNINOIS</div>
                    <div class="auth-brand-subtitle">Fierté &amp; Culture</div>
                </div>
            </div>
        </div>

        <div class="auth-banner-content">
            <h1>@yield('banner_title', "Bienvenue sur\nJe Suis Béninois")</h1>
            <p>@yield('banner_text', "Connectez-vous pour accéder à votre espace et participer à la valorisation de la culture, de l'histoire et du patrimoine béninois.")</p>
        </div>

        <a href="{{ route('home') }}" class="auth-back-home">
            <i class="fa-solid fa-arrow-left"></i> Retour au site
        </a>

    </div>

    {{-- ══ FORMULAIRE ══ --}}
    <div class="auth-form-container">
        <div class="auth-form">
            @yield('content')
        </div>
    </div>

</div>

@stack('scripts')

</body>
</html>