{{-- ═══════════════════════════════════════════
     PARTIAL — FOOTER
     Inclus dans: layouts/app.blade.php
═══════════════════════════════════════════ --}}
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="logo">
                    <div class="logo-flag" style="height:40px;width:28px;">
                        <div class="f-v"></div><div class="f-y"></div><div class="f-r"></div>
                    </div>
                    <div class="logo-text">JE SUIS BÉNINOIS<span>Fierté & Culture</span></div>
                </div>
                <p>Portail d'information et de promotion de la culture, de l'histoire et des traditions du Bénin.</p>
                <div class="footer-socials">
                    <a class="footer-social" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a class="footer-social" href="#" aria-label="X (Twitter)"><i class="fa-brands fa-x-twitter"></i></a>
                    <a class="footer-social" href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a class="footer-social" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Pages</h4>
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('culture.index') }}">Culture</a>
                <a href="{{ route('culture.traditions') }}">Traditions</a>
                <a href="{{ route('culture.patrimoine') }}">Patrimoine</a>
            </div>

            <div class="footer-col">
                <h4>Interviews</h4>
                <a href="{{ route('interviews.index') }}">Toutes les interviews</a>
                @foreach (['Entrepreneurs', 'Artistes', 'Sportifs'] as $nom)
                    @if($footerCategories->get($nom))
                        <a href="{{ route('interviews.index', ['category' => $footerCategories[$nom]->id]) }}">{{ $nom }}</a>
                    @endif
                @endforeach
            </div>

            <div class="footer-col">
                <h4>Le monde</h4>
                @foreach (['Diaspora', 'International', 'Partenaires'] as $nom)
                    @if($footerCategories->get($nom))
                        <a href="{{ route('actualites') }}?category={{ $footerCategories[$nom]->id }}">{{ $nom }}</a>
                    @endif
                @endforeach
            </div>

            <div class="footer-col">
                <h4>Rejoindre</h4>
                <a href="{{ route('register') }}">Devenir contributeur</a>
                <a href="{{ route('financer') }}">Financer</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} Je Suis Béninois. Tous droits réservés.</span>
            <span>Fait avec fierté</span>
        </div>
    </div>
</footer>