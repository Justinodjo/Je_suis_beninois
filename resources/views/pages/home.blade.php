{{-- ═══════════════════════════════════════════════════════
     PAGE D'ACCUEIL — Fidèle Image 4 + Image 6 (mobile)
     Route: GET /  → HomeController@index
     Variables: $articles (6 derniers publiés)
═══════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Je Suis Béninois — La fierté et la richesse du Bénin')

@push('styles')
<style>
/* ══════════ HERO ══════════ */
.hero {
    position: relative;
    height: 520px;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
}
.hero-bg {
    position: absolute; inset: 0;
    background: url('https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1400&q=80') center/cover no-repeat;
}
.hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(
        to right,
        rgba(0,0,0,.72) 0%,
        rgba(0,0,0,.45) 50%,
        rgba(0,0,0,.18) 100%
    );
}
/* Drapeau animé — Image 4 */
.hero-flag {
    position: absolute; top: 32px; right: 40px;
    width: 72px; height: 110px;
    border-radius: 4px; overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,.5);
    animation: flagWave 3s ease-in-out infinite;
    transform-origin: top center;
}
.hero-flag .fv { width:100%; height:33%; background:var(--vert); }
.hero-flag .fy { width:100%; height:33%; background:var(--jaune); }
.hero-flag .fr { width:100%; height:34%; background:var(--rouge); }
@keyframes flagWave {
    0%,100% { transform: rotate(-3deg) skewX(0deg); }
    50%      { transform: rotate(3deg) skewX(3deg); }
}
.hero-content {
    position: relative; z-index: 2;
    padding: 0 0 52px;
    width: 100%;
}
.hero-tag {
    display: inline-block;
    background: rgba(255,215,0,.18);
    border: 1px solid rgba(255,215,0,.5);
    color: var(--jaune);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: 14px;
}
.hero-subtitle {
    font-size: .78rem;
    color: rgba(255,255,255,.7);
    text-transform: uppercase;
    letter-spacing: .12em;
    margin-bottom: 10px;
}
.hero-title {
    font-family: var(--font-h);
    font-size: 3rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.15;
    margin-bottom: 24px;
}
.hero-title .accent { color: var(--jaune); }
.hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }

/* ══════════ ACTUALITÉS PATRIOTIQUES ══════════ */
.actu-section { padding: 64px 0; }
.actu-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 28px;
}
.actu-card-img img { height: 240px; width: 100%; }

/* ══════════ SECTION CULTURE & PATRIMOINE ══════════ */
.culture-band {
    background: var(--vert);
    padding: 56px 0;
    position: relative;
    overflow: hidden;
}
.culture-band::before {
    content: '';
    position: absolute; inset: 0;
    background: url('https://images.unsplash.com/photo-1580130732478-4e339fb33746?w=1400&q=60') center/cover no-repeat;
    opacity: .18;
}
.culture-band-inner {
    position: relative; z-index: 2;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: center;
}
.culture-band-text {}
.culture-band-text .eyebrow {
    display: inline-block;
    background: rgba(255,215,0,.2);
    border: 1px solid rgba(255,215,0,.5);
    color: var(--jaune);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: .76rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 14px;
}
.culture-band-text h2 {
    font-family: var(--font-h);
    font-size: 2rem;
    color: #fff;
    line-height: 1.2;
    margin-bottom: 14px;
}
.culture-band-text h2 .accent { color: var(--jaune); }
.culture-band-text p {
    font-size: .88rem;
    color: rgba(255,255,255,.75);
    line-height: 1.7;
    margin-bottom: 24px;
}
.culture-band-text .nav-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}
.pill {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 600;
    border: 1.5px solid rgba(255,255,255,.4);
    color: rgba(255,255,255,.8);
    cursor: pointer;
    transition: all .18s;
}
.pill:hover, .pill.active { background: var(--jaune); border-color: var(--jaune); color: #1a1a1a; }
.culture-band-btns { display: flex; gap: 12px; flex-wrap: wrap; }
.culture-band-img {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 16px 48px rgba(0,0,0,.4);
    height: 320px;
}
.culture-band-img img { height: 100%; width: 100%; }

/* ══════════ MOBILE (Image 6) ══════════ */
/* Bandeau météo mobile */
.meteo-band {
    display: none;
    background: var(--jaune);
    padding: 8px 16px;
    font-size: .78rem;
    font-weight: 600;
    align-items: center;
    gap: 8px;
}
.meteo-flag-mini { width: 20px; height: 14px; display: flex; border-radius: 2px; overflow: hidden; }
.meteo-flag-mini .fv { flex:1; background:var(--vert); }
.meteo-flag-mini .fy { flex:1; background:var(--jaune); border:1px solid #ddd; }
.meteo-flag-mini .fr { flex:1; background:var(--rouge); }

/* Tabs mobile catégories */
.mobile-tabs {
    display: none;
    overflow-x: auto;
    background: var(--vert);
    padding: 0 16px;
    gap: 0;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.mobile-tabs::-webkit-scrollbar { display: none; }
.mobile-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 12px 16px;
    font-size: .82rem;
    font-weight: 600;
    color: rgba(255,255,255,.7);
    white-space: nowrap;
    border-bottom: 3px solid transparent;
    transition: all .18s;
    cursor: pointer;
}
.mobile-tab.active, .mobile-tab:hover {
    color: var(--jaune);
    border-bottom-color: var(--jaune);
}

/* À la Une slider mobile */
.une-section { padding: 20px 16px 0; }
.une-badge {
    display: inline-block;
    background: var(--rouge);
    color: #fff;
    padding: 3px 10px;
    border-radius: 4px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 12px;
}
.une-slider { position: relative; border-radius: 12px; overflow: hidden; }
.une-slide { position: relative; }
.une-slide img { width: 100%; height: 220px; object-fit: cover; }
.une-slide-overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,.85));
    padding: 32px 16px 16px;
}
.une-slide-overlay h3 {
    font-family: var(--font-h);
    font-size: 1.15rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 12px;
}
.une-dots {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin-top: 10px;
}
.une-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--border);
    cursor: pointer;
    transition: background .18s;
}
.une-dot.active { background: var(--vert); width: 20px; border-radius: 4px; }

/* Sidebar mobile */
.mobile-sidebar {
    background: #fff;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}
.mobile-sidebar-header {
    background: var(--vert);
    color: #fff;
    padding: 12px 16px;
    font-size: .82rem;
    font-weight: 700;
}
.mobile-sidebar-cta {
    padding: 16px;
    background: var(--jaune);
    text-align: center;
}
.mobile-sidebar-cta h4 { font-size: .9rem; font-weight: 700; margin-bottom: 4px; }
.mobile-sidebar-cta p { font-size: .78rem; color: rgba(0,0,0,.6); margin-bottom: 10px; }
.video-card { padding: 12px 16px; border-bottom: 1px solid var(--border); }
.video-thumb { position: relative; border-radius: 8px; overflow: hidden; margin-bottom: 8px; }
.video-thumb img { height: 100px; width: 100%; }
.video-play {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
}
.video-play-btn {
    width: 40px; height: 40px;
    background: rgba(255,255,255,.9);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,.3);
}
.video-title { font-size: .82rem; font-weight: 600; line-height: 1.35; margin-bottom: 6px; }
.video-meta { display: flex; gap: 12px; font-size: .72rem; color: var(--gris-t); }

/* Nav bottom mobile */
.mobile-bottom-nav {
    display: none;
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: var(--vert);
    padding: 8px 0;
    z-index: 100;
    justify-content: space-around;
}
.mobile-bottom-nav a {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    color: rgba(255,255,255,.7);
    font-size: .62rem;
    font-weight: 500;
    padding: 4px 8px;
}
.mobile-bottom-nav a.active { color: var(--jaune); }
.mobile-bottom-nav a svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 2; }

/* ══════════ RESPONSIVE ══════════ */
@media (max-width: 768px) {
    .hero { height: 400px; }
    .hero-title { font-size: 2rem; }
    .hero-flag { width: 50px; height: 76px; right: 20px; top: 20px; }
    .actu-grid { grid-template-columns: 1fr; }
    .culture-band-inner { grid-template-columns: 1fr; }
    .culture-band-img { display: none; }
    .meteo-band { display: flex; }
    .mobile-tabs { display: flex; }
    .mobile-bottom-nav { display: flex; }
    body { padding-bottom: 64px; }
}
@media (max-width: 480px) {
    .hero { height: 320px; }
    .hero-title { font-size: 1.7rem; }
    .hero-actions .btn { flex: 1; justify-content: center; }
}

@media (max-width: 768px) {
    .hero { height: 260px; }
}
.mobile-tab i,
.article-card-meta i,
.hero-actions i,
.video-play-btn i {
    margin-right: 6px;
}

.article-card-meta i {
    color: var(--vert);
    font-size: .75rem;
}

.mobile-tab i {
    font-size: .85rem;
}
.article-card-meta i {
    transition: transform .2s;
}

.article-card-meta span:hover i {
    transform: scale(1.2);
}

.video-meta i {
    margin-right: 4px;
    color: var(--jaune);
}

</style>
@endpush

@section('content')

{{-- ════ BANDEAU MÉTÉO (mobile, Image 6) ════ --}}
<div class="meteo-band">
    <span><i class="fa-solid fa-sun"></i></span>
    <span>Matinée ensoleillée sur Porto-Novo aujourd'hui, température actuelle de 28°C</span>
    <div class="meteo-flag-mini" style="margin-left:auto;"><div class="fv"></div><div class="fy"></div><div class="fr"></div></div>
</div>

{{-- ════ TABS MOBILE CATÉGORIES (Image 6) ════ --}}
<div class="mobile-tabs">
    <div class="mobile-tab active"><i class="fa-solid fa-newspaper"></i>Actualités</div>
    <div class="mobile-tab"><i class="fa-solid fa-landmark"></i>Politique</div>
    <div class="mobile-tab"><i class="fa-solid fa-masks-theater"></i>Culture</div>
    <div class="mobile-tab"><i class="fa-solid fa-futbol"></i>Sport</div>
    <div class="mobile-tab"><i class="fa-solid fa-earth-africa"></i>Diaspora</div>
</div>

{{-- ════ HERO (Image 4) ════ --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>

    {{-- Drapeau animé --}}
    <div class="hero-flag">
        <div class="fv"></div>
        <div class="fy"></div>
        <div class="fr"></div>
    </div>

    <div class="hero-content">
        <div class="container">
            <div class="hero-tag">Célébrons notre fierté</div>
            <h1 class="hero-title">
                La fierté et la richesse<br>
                du <span class="accent">Bénin</span>
            </h1>
            <div class="hero-actions">
                <a href="{{ route('culture.index') }}" class="btn btn-jaune btn-lg">
                    Découvrir ↓
                </a>
                <a href="{{ route('culture.traditions') }}" class="btn btn-outline-blanc btn-lg">
                    Nos Traditions
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ════ À LA UNE SLIDER MOBILE (Image 6) ════ --}}
<div class="une-section" style="display:none;" id="uneMobile">
    <div class="une-badge">À la Une</div>
    <div class="une-slider">
        @if($articles->count() > 0)
        <div class="une-slide">
            <img src="{{ $articles->first()->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=800&q=80' }}"
                 alt="{{ $articles->first()->titre }}">
            <div class="une-slide-overlay">
                <h3>{{ $articles->first()->titre }}</h3>
                <a href="{{ route('culture.article', $articles->first()->slug) }}"
                   class="btn btn-jaune btn-sm">Lire l'article</a>
            </div>
        </div>
        @endif
    </div>
    <div class="une-dots">
        <div class="une-dot active"></div>
        <div class="une-dot"></div>
        <div class="une-dot"></div>
        <div class="une-dot"></div>
        <div class="une-dot"></div>
    </div>
</div>

{{-- ════ ACTUALITÉS PATRIOTIQUES (Image 4) ════ --}}
<section class="actu-section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Actualités <span class="accent">Patriotiques</span></h2>
                <div class="section-divider"></div>
            </div>
            <a href="{{ route('culture.index') }}" class="section-link">Voir tout →</a>
        </div>

        <div class="actu-grid">
            @forelse($articles as $article)
            <article class="article-card">
                <div class="article-card-img actu-card-img">
                    <a href="{{ route('culture.article', $article->slug) }}">
                        <img src="{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=600&q=80' }}"
                             alt="{{ $article->titre }}">
                    </a>
                    {{-- Badges catégories --}}
                    <div style="position:absolute;top:12px;left:12px;display:flex;gap:6px;flex-wrap:wrap;">
                        @foreach($article->categories->take(3) as $cat)
                        <span class="badge" style="background:{{ $cat->couleur ?? 'var(--vert)' }};color:#fff;">
                            {{ strtoupper($cat->nom) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                <div class="article-card-body">
                    <h3 class="article-card-title">
                        <a href="{{ route('culture.article', $article->slug) }}">{{ $article->titre }}</a>
                    </h3>
                    <p class="article-card-excerpt">
                        {{ Str::limit($article->extrait ?? strip_tags($article->contenu), 130) }}
                    </p>
                    <div class="article-card-meta">
                        <span><i class="fa-solid fa-pen-nib"></i> {{ $article->user?->name ?? 'Admin' }}</span>
                        <span><i class="fa-regular fa-calendar"></i>{{ $article->created_at?->diffForHumans() }}</span>
                        <span><i class="fa-regular fa-eye"></i>{{ number_format($article->nb_vues) }}</span>
                        <span><i class="fa-regular fa-heart"></i> {{ $article->nb_likes }}</span>
                    </div>
                </div>
            </article>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:var(--gris-t);">
                <div style="font-size:3rem;margin-bottom:16px;"><i class="fa-solid fa-newspaper"></i></div>
                <p style="font-size:1rem;margin-bottom:20px;">Aucun article disponible pour le moment.</p>
                @auth
                <a href="{{ route('dashboard.articles') }}" class="btn btn-vert">Créer le premier article</a>
                @endauth
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ════ SECTION CULTURE & PATRIMOINE (Image 4 bas) ════ --}}
<section class="culture-band">
    <div class="container">
        <div class="culture-band-inner">
            <div class="culture-band-text">
                <div class="eyebrow"><i class="fa-solid fa-landmark"></i> Explore · Passé · Présent · 4 à découvrir</div>
                <h2>Culture & <span class="accent">Patrimoine</span></h2>
                <p>
                    Découvrez la richesse culturelle du Bénin, berceau du Vodoun
                    et des Amazones d'Abomey. De nos traditions ancestrales aux
                    palais royaux classés UNESCO, plongez dans notre patrimoine vivant.
                </p>
                <div class="nav-pills">
                    <a href="{{ route('culture.index') }}" class="pill active">Tout</a>
                    <a href="{{ route('culture.traditions') }}" class="pill">Traditions</a>
                    <a href="{{ route('culture.patrimoine') }}" class="pill">Patrimoine</a>
                    <a href="{{ route('culture.index') }}?type=arts" class="pill">Arts</a>
                </div>
                <div class="culture-band-btns">
                    <a href="{{ route('culture.traditions') }}" class="btn btn-jaune">Nos Traditions</a>
                    <a href="{{ route('culture.patrimoine') }}" class="btn btn-outline-blanc">Notre Patrimoine</a>
                </div>
            </div>
            <div class="culture-band-img">
                <img src="https://images.unsplash.com/photo-1580130732478-4e339fb33746?w=800&q=80"
                     alt="Patrimoine béninois">
            </div>
        </div>
    </div>
</section>

{{-- ════ SIDEBAR MOBILE (Image 6) ════ --}}
<div style="display:none;" id="mobileSidebar">
    <div class="container" style="padding:20px 16px;">

        {{-- Participer CTA --}}
        <div class="mobile-sidebar" style="margin-bottom:20px;">
            <div class="mobile-sidebar-cta">
                <h4>Participer</h4>
                <p>Partagez notre histoire, envoyez vos articles.</p>
                <a href="#" class="btn btn-vert btn-sm" style="width:100%;justify-content:center;">
                    Devenir Contributeur
                </a>
            </div>
        </div>

        {{-- Vidéo du jour --}}
        <div class="mobile-sidebar">
            <div class="mobile-sidebar-header">LA VIDÉO DU JOUR</div>
            <div class="video-card">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=400&q=80" alt="Vidéo">
                    <div class="video-play">
                        <div class="video-play-btn"><i class="fa-solid fa-play"></i></div>
                    </div>
                </div>
                <div class="video-title">#JE_SUIS_BÉNINOIS : Retour sur les temps forts de l'année</div>
                <div class="video-meta">
                    <span><i class="fa-solid fa-heart"></i>322</span>
                    <span><i class="fa-solid fa-comment"></i></span>
                    <span>Il y a 8min</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Afficher les sections mobiles sur petit écran
function checkMobile() {
    const isMobile = window.innerWidth <= 768;
    const uneMobile = document.getElementById('uneMobile');
    const mobileSidebar = document.getElementById('mobileSidebar');
    if (uneMobile) uneMobile.style.display = isMobile ? 'block' : 'none';
    if (mobileSidebar) mobileSidebar.style.display = isMobile ? 'block' : 'none';
}
checkMobile();
window.addEventListener('resize', checkMobile);

// Tabs mobile
document.querySelectorAll('.mobile-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.mobile-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
    });
});
</script>
@endpush