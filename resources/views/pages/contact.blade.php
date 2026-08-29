{{-- resources/views/pages/contact.blade.php --}}
@extends('layouts.app')

@section('title', 'Contact — Je Suis Béninois')

@section('content')

<section class="contact-hero pattern-bg">
    <div class="container">
        <span class="badge badge-jaune">Nous écrire</span>
        <h1>Contactez-nous</h1>
        <p>Une question, une suggestion, une envie de contribuer ? Écrivez-nous, nous vous répondons rapidement.</p>
    </div>
</section>

<section class="contact-section">
    <div class="container contact-grid">

        {{-- ═══ COLONNE INFOS ═══ --}}
        <div class="contact-info">
            <h2>Parlons-en</h2>
            <p class="contact-info-lead">
                Que vous soyez un membre de la diaspora, un contributeur potentiel ou simplement curieux,
                notre équipe est à votre écoute.
            </p>

            <div class="contact-info-list">
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <h4>Email</h4>
                        <p>contact@jesuisbeninois.com</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <h4>Basé à</h4>
                        <p>Cotonou, Bénin</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="fa-solid fa-clock"></i></div>
                    <div>
                        <h4>Réponse</h4>
                        <p>Sous 24 à 48h ouvrées</p>
                    </div>
                </div>
            </div>

            <div class="footer-socials contact-socials">
                <a class="footer-social" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a class="footer-social" href="#" aria-label="X (Twitter)"><i class="fa-brands fa-x-twitter"></i></a>
                <a class="footer-social" href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                <a class="footer-social" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        {{-- ═══ COLONNE FORMULAIRE ═══ --}}
        <div class="contact-card">

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="contact-form" id="contactForm">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom complet</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}"
                               placeholder="Votre nom" required maxlength="120">
                        @error('nom') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="votre@email.com" required maxlength="180">
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="sujet">Sujet</label>
                    <input type="text" id="sujet" name="sujet" value="{{ old('sujet') }}"
                           placeholder="De quoi voulez-vous parler ?" maxlength="180">
                    @error('sujet') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6"
                              placeholder="Votre message..." required maxlength="3000">{{ old('message') }}</textarea>
                    <span class="form-hint" id="charCount">0 / 3000</span>
                    @error('message') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary contact-submit">
                    <i class="fa-solid fa-paper-plane"></i>
                    Envoyer le message
                </button>
            </form>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ═══ HERO CONTACT ═══ */
.contact-hero {
    padding: 64px 0 48px;
    text-align: center;
}
.contact-hero h1 {
    font-family: var(--font-titre);
    font-size: clamp(2rem, 4vw, 2.8rem);
    margin: 14px 0 12px;
    color: var(--text);
}
.contact-hero p {
    max-width: 560px;
    margin: 0 auto;
    color: var(--text-l);
    font-size: 1rem;
}

/* ═══ GRID PRINCIPAL ═══ */
.contact-section { padding: 0 0 80px; }
.contact-grid {
    display: grid;
    grid-template-columns: 0.85fr 1.15fr;
    gap: 48px;
    align-items: start;
}

/* ── Colonne infos ── */
.contact-info h2 {
    font-family: var(--font-titre);
    font-size: 1.6rem;
    margin-bottom: 12px;
    color: var(--vert);
}
.contact-info-lead {
    color: var(--text-l);
    font-size: 0.92rem;
    line-height: 1.7;
    margin-bottom: 32px;
}
.contact-info-list {
    display: flex;
    flex-direction: column;
    gap: 22px;
    margin-bottom: 32px;
}
.contact-info-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}
.contact-info-icon {
    flex-shrink: 0;
    width: 46px; height: 46px;
    border-radius: 12px;
    background: rgba(27,94,32,0.08);
    color: var(--vert);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.05rem;
}
.contact-info-item h4 {
    font-size: 0.88rem;
    color: var(--text);
    margin-bottom: 2px;
}
.contact-info-item p {
    font-size: 0.86rem;
    color: var(--text-l);
}
.contact-socials .footer-social {
    background: var(--gris-c);
    color: var(--vert);
}
.contact-socials .footer-social:hover { color: #fff; }

/* ── Carte formulaire ── */
.contact-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 36px;
    box-shadow: var(--shadow);
}

.alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    border-radius: 10px;
    font-size: 0.86rem;
    margin-bottom: 22px;
}
.alert-success {
    background: rgba(27,94,32,0.08);
    color: var(--vert);
    border: 1px solid rgba(27,94,32,0.2);
}

.contact-form { display: flex; flex-direction: column; gap: 18px; }
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}
.form-group { display: flex; flex-direction: column; }
.form-group label {
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--text-l);
}
.form-group input,
.form-group textarea {
    padding: 12px 14px;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 0.9rem;
    font-family: var(--font-body);
    color: var(--text);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    resize: vertical;
}
.form-group input:focus,
.form-group textarea:focus {
    border-color: var(--vert);
    box-shadow: 0 0 0 3px rgba(27,94,32,.12);
}
.form-hint {
    align-self: flex-end;
    font-size: 0.72rem;
    color: var(--gris-t);
    margin-top: 4px;
}
.form-error {
    font-size: 0.78rem;
    color: var(--rouge);
    margin-top: 4px;
}
.contact-submit {
    margin-top: 8px;
    justify-content: center;
    padding: 13px 24px;
    font-size: 0.92rem;
}

/* ── Responsive ── */
@media (max-width: 860px) {
    .contact-grid { grid-template-columns: 1fr; gap: 40px; }
}
@media (max-width: 560px) {
    .form-row { grid-template-columns: 1fr; }
    .contact-card { padding: 24px; }
}
</style>
@endpush

@push('scripts')
<script>
const messageField = document.getElementById('message');
const charCount     = document.getElementById('charCount');

if (messageField && charCount) {
    const updateCount = () => {
        charCount.textContent = `${messageField.value.length} / 3000`;
    };
    messageField.addEventListener('input', updateCount);
    updateCount();
}
</script>
@endpush