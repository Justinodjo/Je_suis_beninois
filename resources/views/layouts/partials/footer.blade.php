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
    <a class="footer-social" href="#" aria-label="Facebook">
        <i class="fa-brands fa-facebook-f"></i>
    </a>

    <a class="footer-social" href="#" aria-label="X (Twitter)">
        <i class="fa-brands fa-x-twitter"></i>
    </a>

    <a class="footer-social" href="#" aria-label="YouTube">
        <i class="fa-brands fa-youtube"></i>
    </a>

    <a class="footer-social" href="#" aria-label="Instagram">
        <i class="fa-brands fa-instagram"></i>
    </a>
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
                <a href="#">Entrepreneurs</a>
                <a href="#">Artistes</a>
                <a href="#">Sportifs</a>
            </div>
            <div class="footer-col">
                <h4>Le monde</h4>
                <a href="#">Diaspora</a>
                <a href="#">International</a>
                <a href="#">Partenaires</a>
            </div>
            <div class="footer-col">
                <h4>Rejoindre</h4>
                <a href="#">Devenir contributeur</a>
                <a href="#">Financer</a>
                <a href="#">Contact</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} Je Suis Béninois. Tous droits réservés.</span>
            <span>Fait avec fierté</span>
        </div>
    </div>
</footer>