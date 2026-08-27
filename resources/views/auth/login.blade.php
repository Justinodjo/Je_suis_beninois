<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Connexion - Je Suis Béninois</title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                radial-gradient(
                    circle at top left,
                    rgba(27,94,32,.12),
                    transparent 35%
                ),
                radial-gradient(
                    circle at bottom right,
                    rgba(255,215,0,.12),
                    transparent 35%
                ),
                #f5f5f0;

            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 1000px;

            min-height: 600px;

            display: grid;
            grid-template-columns: 1fr 1fr;

            background: white;

            border-radius: 20px;

            overflow: hidden;

            box-shadow:
                0 20px 60px rgba(0,0,0,.15);
        }


        /*
        |--------------------------------------------------------------------------
        | PARTIE GAUCHE
        |--------------------------------------------------------------------------
        */

        .login-banner {

            background:
                linear-gradient(
                    135deg,
                    rgba(27,94,32,.95),
                    rgba(46,125,50,.95)
                );

            color: white;

            padding: 60px;

            display: flex;
            flex-direction: column;
            justify-content: space-between;

            position: relative;

            overflow: hidden;
        }

        .login-banner::before {

            content: "";

            position: absolute;

            width: 300px;
            height: 300px;

            border: 60px solid rgba(255,255,255,.05);

            border-radius: 50%;

            top: -100px;
            right: -100px;
        }

        .brand {
            position: relative;
            z-index: 2;
        }

        .brand-logo {

            display: flex;

            align-items: center;

            gap: 12px;

            margin-bottom: 20px;
        }

        .brand-logo img {

            width: 60px;
            height: 60px;

            object-fit: cover;

            border-radius: 8px;

            background: white;
        }

        .brand-title {

            font-family: var(--font-titre);

            font-size: 1.4rem;

            font-weight: 700;
        }

        .brand-subtitle {

            font-size: .75rem;

            color: var(--jaune);

            text-transform: uppercase;

            letter-spacing: 2px;
        }

        .banner-content {

            position: relative;

            z-index: 2;
        }

        .banner-content h1 {

            font-family: var(--font-titre);

            font-size: 2.5rem;

            line-height: 1.2;

            margin-bottom: 20px;
        }

        .banner-content p {

            color: rgba(255,255,255,.75);

            line-height: 1.7;
        }

        .back-home {

            position: relative;

            z-index: 2;

            color: white;

            font-size: .9rem;

            opacity: .8;

            text-decoration: none;
        }

        .back-home:hover {

            opacity: 1;
        }


        /*
        |--------------------------------------------------------------------------
        | FORMULAIRE
        |--------------------------------------------------------------------------
        */

        .login-form-container {

            padding: 60px;

            display: flex;

            align-items: center;
        }

        .login-form {

            width: 100%;
        }

        .login-form h2 {

            font-family: var(--font-titre);

            font-size: 2rem;

            margin-bottom: 10px;

            color: var(--vert);
        }

        .login-form .description {

            color: var(--text-l);

            margin-bottom: 35px;
        }


        .form-group {

            margin-bottom: 20px;
        }

        label {

            display: block;

            font-size: .85rem;

            font-weight: 600;

            margin-bottom: 8px;
        }

        .input-wrapper {

            position: relative;
        }

        input {

            width: 100%;

            padding: 13px 15px;

            border: 1px solid var(--border);

            border-radius: 10px;

            font-size: .95rem;

            outline: none;

            transition: .2s;
        }

        input:focus {

            border-color: var(--vert);

            box-shadow:
                0 0 0 3px rgba(27,94,32,.1);
        }

        .password-toggle {

            position: absolute;

            right: 15px;

            top: 50%;

            transform: translateY(-50%);

            cursor: pointer;

            color: var(--text-l);
        }


        .login-button {

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

            margin-top: 10px;
        }

        .login-button:hover {

            background: var(--vert-l);
        }

        .login-button:disabled {

            opacity: .7;

            cursor: not-allowed;
        }


        .register-link {

            text-align: center;

            margin-top: 25px;

            font-size: .9rem;

            color: var(--text-l);
        }

        .register-link a {

            color: var(--vert);

            font-weight: 600;

            text-decoration: none;
        }


        .error-message {

            display: none;

            margin-bottom: 20px;

            padding: 12px;

            border-radius: 8px;

            background: #ffebee;

            color: var(--rouge);

            font-size: .85rem;
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        @media(max-width: 768px) {

            .login-container {

                grid-template-columns: 1fr;

                max-width: 500px;
            }

            .login-banner {

                display: none;
            }

            .login-form-container {

                padding: 40px 30px;
            }
        }

    </style>

</head>

<body>


<div class="login-container">


    {{-- PARTIE GAUCHE --}}
    <div class="login-banner">

        <div class="brand">

            <div class="brand-logo">

                <img
                    src="{{ asset('images/logo.jpeg') }}"
                    alt="Je Suis Béninois"
                >

                <div>

                    <div class="brand-title">
                        JE SUIS BÉNINOIS
                    </div>

                    <div class="brand-subtitle">
                        Fierté & Culture
                    </div>

                </div>

            </div>

        </div>


        <div class="banner-content">

            <h1>
                Bienvenue sur
                Je Suis Béninois
            </h1>

            <p>
                Connectez-vous pour accéder à votre espace
                et participer à la valorisation de la culture,
                de l'histoire et du patrimoine béninois.
            </p>

        </div>


        <a
            href="{{ route('home') }}"
            class="back-home"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Retour au site

        </a>

    </div>



    {{-- FORMULAIRE --}}
    <div class="login-form-container">

        <div class="login-form">

            <h2>
                Connexion
            </h2>

            <p class="description">

                Entrez vos informations pour accéder
                à votre compte.

            </p>


            <div
                id="loginError"
                class="error-message"
            ></div>


            <form id="loginForm">


                {{-- EMAIL --}}
                <div class="form-group">

                    <label for="email">

                        Adresse email

                    </label>

                    <input
                        type="email"
                        id="email"
                        placeholder="exemple@email.com"
                        required
                    >

                </div>



                {{-- PASSWORD --}}
                <div class="form-group">

                    <label for="password">

                        Mot de passe

                    </label>


                    <div class="input-wrapper">

                        <input
                            type="password"
                            id="password"
                            placeholder="••••••••"
                            required
                        >

                        <i
                            class="fa-solid fa-eye password-toggle"
                            id="togglePassword"
                        ></i>

                    </div>

                </div>



                <button
                    type="submit"
                    class="login-button"
                    id="loginButton"
                >

                    <span id="loginButtonText">

                        Se connecter

                    </span>

                </button>


            </form>


            <div class="register-link">

                Vous n'avez pas encore de compte ?

                <a href="{{ route('register') }}">

                    Créer un compte

                </a>

            </div>

        </div>

    </div>

</div>


<script>


/*
|--------------------------------------------------------------------------
| AFFICHER / CACHER LE MOT DE PASSE
|--------------------------------------------------------------------------
*/

document
    .getElementById('togglePassword')
    .addEventListener(
        'click',
        function () {

            const password =
                document.getElementById('password');


            if (
                password.type === 'password'
            ) {

                password.type = 'text';

                this.classList.remove(
                    'fa-eye'
                );

                this.classList.add(
                    'fa-eye-slash'
                );

            } else {

                password.type = 'password';

                this.classList.remove(
                    'fa-eye-slash'
                );

                this.classList.add(
                    'fa-eye'
                );

            }

        }
    );



/*
|--------------------------------------------------------------------------
| LOGIN JWT
|--------------------------------------------------------------------------
*/

document
    .getElementById('loginForm')
    .addEventListener(
        'submit',
        async function (event) {

            event.preventDefault();


            const email =
                document
                    .getElementById('email')
                    .value;


            const password =
                document
                    .getElementById('password')
                    .value;


            const errorDiv =
                document
                    .getElementById('loginError');


            const button =
                document
                    .getElementById('loginButton');


            const buttonText =
                document
                    .getElementById('loginButtonText');


            errorDiv.style.display =
                'none';


            button.disabled = true;

            buttonText.textContent =
                'Connexion...';


            try {

                const response =
                    await fetch(
                        '/api/v1/login',
                        {

                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json'

                            },

                            body:
                                JSON.stringify({

                                    email,
                                    password

                                })

                        }
                    );


                const data =
                    await response.json();


                /*
                |--------------------------------------------------------------------------
                | CONNEXION RÉUSSIE
                |--------------------------------------------------------------------------
                */

                if (
                    response.ok
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT
                    |--------------------------------------------------------------------------
                    |
                    | Ton AuthController retourne :
                    |
                    | 'token' => $token
                    |
                    | Donc :
                    |
                    | data.token
                    |
                    |--------------------------------------------------------------------------
                    */

                    localStorage.setItem(
                        'auth_token',
                        data.token
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | REDIRECTION
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data.user &&
                        data.user.role === 'admin'
                    ) {

                        window.location.href =
                            '/dashboard';

                    } else {

                        window.location.href =
                            '/';

                    }

                }

                /*
                |--------------------------------------------------------------------------
                | ERREUR LOGIN
                |--------------------------------------------------------------------------
                */

                else {

                    errorDiv.style.display =
                        'block';


                    errorDiv.textContent =
                        data.message ||
                        'Email ou mot de passe incorrect.';

                }

            }

            catch (error) {

                console.error(
                    error
                );


                errorDiv.style.display =
                    'block';


                errorDiv.textContent =
                    'Impossible de contacter le serveur.';

            }

            finally {

                button.disabled =
                    false;


                buttonText.textContent =
                    'Se connecter';

            }

        }
    );


</script>


</body>

</html>