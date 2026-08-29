@extends('layouts.app')

@section('title', 'Accueil — Je Suis Béninois')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   TOKENS LOCAUX
═══════════════════════════════════════════ */
:root {
    --vert-encre: #0F3D14;
    --sable: #F1ECE0;
}

/* ═══════════════════════════════════════════
   MOTIF WAX — SIGNATURE DE LA PAGE
   Séparateur de section inspiré du tissu Ankara
═══════════════════════════════════════════ */
.wax-divider {
    height: 14px;
    background:
        conic-gradient(from 45deg at 50% 50%, var(--vert) 90deg, transparent 90deg 180deg, var(--jaune) 180deg 270deg, transparent 270deg) ,
        conic-gradient(from 225deg at 50% 50%, var(--rouge) 90deg, transparent 90deg 180deg, var(--vert) 180deg 270deg, transparent 270deg);
    background-size: 14px 14px, 14px 14px;
    background-position: 0 0, 7px 0;
    opacity: .92;
}

/* ═══════════════════════════════════════════
   HERO ÉDITORIAL — UNE DE JOURNAL ASYMÉTRIQUE
═══════════════════════════════════════════ */
/* ═══════════════════════════════════════════
   HERO ÉDITORIAL — SLIDER DE LA UNE
═══════════════════════════════════════════ */
.editorial-hero {
    background: var(--vert-encre);
    padding: 40px 0 0;
}
.editorial-hero-grid {
    display: grid;
    grid-template-columns: 1.7fr 1fr;
    gap: 0;
    min-height: 560px;
}

/* ── Slider vedette ── */
.hero-feature {
    position: relative;
    overflow: hidden;
    min-height: 560px;
}
.hero-slide {
    position: absolute; inset: 0;
    display: flex;
    align-items: flex-end;
    opacity: 0;
    visibility: hidden;
    transition: opacity 1.1s ease;
    z-index: 1;
}
.hero-slide.active { opacity: 1; visibility: visible; z-index: 2; }

.hero-slide-bg {
    position: absolute; inset: 0;
    background-size: cover;
    background-position: center;
    transform: scale(1.04);
    transition: transform 7s cubic-bezier(.16,1,.3,1);
}
.hero-slide.active .hero-slide-bg { transform: scale(1); }

.hero-slide-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(6,20,8,.94) 0%, rgba(6,20,8,.55) 45%, rgba(6,20,8,.05) 80%);
}
.hero-slide-content {
    position: relative; z-index: 2;
    padding: 44px 48px;
    max-width: 640px;
}
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: var(--font-body); font-size: .72rem; font-weight: 700;
    letter-spacing: .14em; text-transform: uppercase; color: var(--jaune);
    margin-bottom: 18px;
}
.hero-eyebrow::before { content: ''; width: 22px; height: 2px; background: var(--jaune); }
.hero-feature-title {
    font-family: var(--font-titre);
    font-size: clamp(1.9rem, 3.4vw, 3.1rem);
    font-weight: 700; line-height: 1.08; color: #fff;
    margin-bottom: 16px; letter-spacing: -.01em;
}
.hero-feature-excerpt {
    font-family: var(--font-serif); font-size: 1.02rem; font-style: italic;
    color: rgba(255,255,255,.72); line-height: 1.6; margin-bottom: 26px; max-width: 520px;
}
.hero-feature-cta {
    display: inline-flex; align-items: center; gap: 10px; color: #fff;
    font-size: .88rem; font-weight: 600; padding-bottom: 3px;
    border-bottom: 1.5px solid rgba(255,255,255,.35);
    transition: border-color .25s, gap .25s;
}
.hero-feature-cta:hover { border-color: var(--jaune); gap: 14px; }
.hero-feature-cta i { color: var(--jaune); }

/* Dots de navigation du slider */
.hero-dots {
    position: absolute; bottom: 20px; right: 48px; z-index: 3;
    display: flex; gap: 8px;
}
.hero-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(255,255,255,.35); border: none; cursor: pointer; padding: 0;
    transition: background .25s, transform .25s;
}
.hero-dot.active { background: var(--jaune); transform: scale(1.25); }

/* ── Colonne "aussi à la une" ── */
.hero-list { background: #0B2C0F; display: flex; flex-direction: column; }
.hero-list-header {
    padding: 22px 28px 14px;
    font-family: var(--font-body); font-size: .72rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.45);
    border-bottom: 1px solid rgba(255,255,255,.08);
}
.hero-list-item {
    display: flex; gap: 16px; padding: 20px 28px;
    border-bottom: 1px solid rgba(255,255,255,.08);
    transition: background .2s, box-shadow .2s;
}
.hero-list-item:hover { background: rgba(255,255,255,.04); }
.hero-list-item:last-child { border-bottom: none; flex: 1; }
.hero-list-item.is-active {
    background: rgba(255,255,255,.07);
    box-shadow: inset 3px 0 0 var(--jaune);
}
.hero-list-rank {
    font-family: var(--font-titre); font-size: 1.6rem; font-weight: 700;
    color: var(--jaune); opacity: .55; line-height: 1; flex-shrink: 0;
}
.hero-list-item.is-active .hero-list-rank { opacity: 1; }
.hero-list-thumb { width: 64px; height: 64px; border-radius: 8px; overflow: hidden; flex-shrink: 0; }
.hero-list-thumb img { width: 100%; height: 100%; object-fit: cover; }
.hero-list-text { min-width: 0; }
.hero-list-cat {
    font-size: .66rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: var(--jaune); margin-bottom: 4px;
}
.hero-list-title { font-family: var(--font-titre); font-size: .92rem; font-weight: 600; line-height: 1.35; color: #fff; }
.hero-list-title a:hover { color: var(--jaune); }

@media (max-width: 900px) {
    .editorial-hero-grid { grid-template-columns: 1fr; min-height: 0; }
    .hero-feature { min-height: 400px; }
    .hero-slide-content { padding: 32px 24px; }
    .hero-list-item:last-child { flex: none; }
    .hero-dots { right: 24px; bottom: 14px; }
}

/* ═══════════════════════════════════════════
   CATEGORIES — TABS AVEC INDICATEUR GLISSANT
═══════════════════════════════════════════ */
.categories-section {
    background: var(--sable);
    padding: 56px 0 64px;
}
.section-kicker {
    display: flex;
    align-items: baseline;
    gap: 14px;
    margin-bottom: 32px;
}
.section-kicker h2 {
    font-family: var(--font-titre);
    font-size: clamp(1.5rem, 2.4vw, 2rem);
    font-weight: 700;
    color: var(--text);
}
.section-kicker .accent { color: var(--vert); }
.section-kicker-line {
    flex: 1;
    height: 1px;
    background: var(--border);
}

.category-tabs-wrap {
    position: relative;
    margin-bottom: 36px;
}
.category-tabs {
    display: flex;
    gap: 6px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    border-bottom: 1px solid var(--border);
    position: relative;
}
.category-tabs::-webkit-scrollbar { display: none; }

.category-tab {
    padding: 12px 20px;
    font-size: .86rem;
    font-weight: 600;
    color: var(--text-l);
    background: none;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
    transition: color .2s;
}
.category-tab:hover { color: var(--vert); }
.category-tab.active { color: var(--vert); }
.category-tab .count {
    background: var(--gris-c);
    color: var(--gris-t);
    padding: 1px 8px;
    border-radius: 10px;
    font-size: .68rem;
    font-weight: 700;
}
.category-tab.active .count { background: var(--vert); color: #fff; }

.tab-indicator {
    position: absolute;
    bottom: -1px;
    height: 2.5px;
    background: var(--vert);
    border-radius: 2px;
    transition: left .3s cubic-bezier(.4,0,.2,1), width .3s cubic-bezier(.4,0,.2,1);
}

.category-content { display: none; }
.category-content.active { display: block; }

.articles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 26px;
}

/* ── Carte article modernisée ── */
.mod-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(15,61,20,.06);
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
    opacity: 0;
    transform: translateY(18px);
}
.mod-card.reveal { opacity: 1; transform: translateY(0); }
.mod-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(15,61,20,.14);
}
.mod-card-img { position: relative; overflow: hidden; height: 190px; }
.mod-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s cubic-bezier(.4,0,.2,1); }
.mod-card:hover .mod-card-img img { transform: scale(1.08); }
.mod-card-badge {
    position: absolute; top: 14px; left: 14px;
    background: rgba(27,94,32,.92);
    backdrop-filter: blur(4px);
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: .66rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
}
.mod-card-body { padding: 20px; }
.mod-card-title {
    font-family: var(--font-titre);
    font-size: 1.05rem;
    font-weight: 700;
    line-height: 1.35;
    color: var(--text);
    margin-bottom: 10px;
}
.mod-card-title a:hover { color: var(--vert); }
.mod-card-excerpt {
    font-size: .84rem;
    color: var(--text-l);
    line-height: 1.6;
    margin-bottom: 16px;
}
.mod-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px solid var(--gris-c);
}
.mod-card-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: .74rem;
    color: var(--gris-t);
}
.mod-card-meta span { display: flex; align-items: center; gap: 5px; }
.mod-card-meta i { color: var(--vert); font-size: .78rem; }

.empty-cat {
    grid-column: 1/-1;
    text-align: center;
    padding: 56px 20px;
    color: var(--text-l);
}
.empty-cat i { font-size: 2.6rem; color: var(--border); margin-bottom: 14px; display: block; }

.cat-cta { text-align: center; margin-top: 36px; }

/* ═══════════════════════════════════════════
   ACCESSIBILITÉ — FOCUS VISIBLE
═══════════════════════════════════════════ */
.category-tab:focus-visible,
.hero-feature-cta:focus-visible,
.mod-card a:focus-visible {
    outline: 2px solid var(--jaune);
    outline-offset: 3px;
    border-radius: 4px;
}

/* ═══════════════════════════════════════════
   RÉDUCTION DE MOUVEMENT
═══════════════════════════════════════════ */
@media (prefers-reduced-motion: reduce) {
    .hero-feature-bg,
    .mod-card,
    .mod-card-img img,
    .tab-indicator { transition: none !important; }
    .mod-card { opacity: 1; transform: none; }
    .hero-slide, .hero-slide-bg { transition: none !important; }
}

/* ═══════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════ */
@media (max-width: 1024px) {
    .articles-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
    .articles-grid { grid-template-columns: 1fr; }
    .hero-list-header { padding: 18px 20px 12px; }
    .hero-list-item { padding: 16px 20px; }
}
</style>
@endpush

@section('content')

{{-- ════ HERO ÉDITORIAL — UNE DE JOURNAL ════ --}}
@php
    $heroSlides = $featuredArticles->take(4)->values();
@endphp

<section class="editorial-hero">
    <div class="editorial-hero-grid">

        {{-- ── Slider vedette ── --}}
        <div class="hero-feature" id="heroFeature">
            @forelse($heroSlides as $i => $article)
            <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}">
                <div class="hero-slide-bg" style="background-image:url('{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1400&q=80' }}');"></div>
                <div class="hero-slide-overlay"></div>
                <div class="hero-slide-content">
                    <div class="hero-eyebrow">
                        <i class="fa-solid fa-star"></i>
                        {{ $article->categories->first()?->nom ?? 'À la Une' }}
                    </div>
                    <h1 class="hero-feature-title">{{ $article->titre }}</h1>
                    <p class="hero-feature-excerpt">
                        {{ Str::limit($article->extrait ?? strip_tags($article->contenu), 160) }}
                    </p>
                    <a href="{{ route('culture.article', $article->slug) }}" class="hero-feature-cta">
                        Lire l'article <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="hero-slide active">
                <div class="hero-slide-content">
                    <h1 class="hero-feature-title">Aucun article à la une</h1>
                </div>
            </div>
            @endforelse

            @if($heroSlides->count() > 1)
            <div class="hero-dots">
                @foreach($heroSlides as $i => $article)
                <button type="button" class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-slide-btn="{{ $i }}" aria-label="Article {{ $i + 1 }}"></button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── Aussi à la une ── --}}
        <div class="hero-list">
            <div class="hero-list-header">Aussi à la une</div>
            @forelse($heroSlides->slice(1) as $i => $article)
            <a href="{{ route('culture.article', $article->slug) }}" class="hero-list-item" data-list-slide="{{ $i + 1 }}">
                <span class="hero-list-rank">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="hero-list-thumb">
                    <img src="{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=200&q=70' }}"
                         alt="{{ $article->titre }}">
                </div>
                <div class="hero-list-text">
                    <div class="hero-list-cat">{{ $article->categories->first()?->nom ?? 'Culture' }}</div>
                    <div class="hero-list-title">{{ Str::limit($article->titre, 60) }}</div>
                </div>
            </a>
            @empty
            <div style="padding:28px;color:rgba(255,255,255,.5);font-size:.85rem;">
                Pas d'autres articles à la une pour le moment.
            </div>
            @endforelse
        </div>

    </div>
</section>

{{-- ════ MOTIF WAX — SÉPARATEUR SIGNATURE ════ --}}
<div class="wax-divider"></div>

{{-- ════ SECTIONS PAR CATÉGORIE ════ --}}
<section class="categories-section">
    <div class="container">

        <div class="section-kicker">
            <h2>Actualités par <span class="accent">catégorie</span></h2>
            <div class="section-kicker-line"></div>
        </div>

        {{-- Tabs --}}
        <div class="category-tabs-wrap">
            <div class="category-tabs" id="categoryTabs">
                @foreach($categories as $index => $category)
                <button class="category-tab {{ $index === 0 ? 'active' : '' }}"
                        onclick="switchCategory('cat-{{ $category->id }}', this)"
                        data-category="cat-{{ $category->id }}">
                    @if($category->icone)
                        {{ $category->icone }}
                    @else
                        <i class="fa-solid fa-folder"></i>
                    @endif
                    {{ $category->nom }}
                    <span class="count">{{ $category->articles_count ?? 0 }}</span>
                </button>
                @endforeach
            </div>
            <div class="tab-indicator" id="tabIndicator"></div>
        </div>

        {{-- Contenus par catégorie --}}
        @foreach($categories as $index => $category)
        <div class="category-content {{ $index === 0 ? 'active' : '' }}" id="cat-{{ $category->id }}">
            <div class="articles-grid">
                @forelse($articlesByCategory[$category->id] ?? [] as $article)
                <article class="mod-card">
                    <div class="mod-card-img">
                        <a href="{{ route('culture.article', $article->slug) }}">
                            <img src="{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=600&q=80' }}"
                                 alt="{{ $article->titre }}">
                        </a>
                        <span class="mod-card-badge">{{ $category->nom }}</span>
                    </div>
                    <div class="mod-card-body">
                        <h3 class="mod-card-title">
                            <a href="{{ route('culture.article', $article->slug) }}">
                                {{ Str::limit($article->titre, 70) }}
                            </a>
                        </h3>
                        <p class="mod-card-excerpt">
                            {{ Str::limit($article->extrait ?? strip_tags($article->contenu), 100) }}
                        </p>
                        <div class="mod-card-footer">
                            <div class="mod-card-meta">
                                <span><i class="fa-regular fa-calendar"></i> {{ $article->created_at?->diffForHumans() }}</span>
                                <span><i class="fa-regular fa-eye"></i> {{ number_format($article->nb_vues) }}</span>
                            </div>
                        </div>
                    </div>
                </article>
                @empty
                <div class="empty-cat">
                    <i class="fa-solid fa-inbox"></i>
                    <p>Aucun article dans cette catégorie pour le moment.</p>
                </div>
                @endforelse
            </div>

            @if(count($articlesByCategory[$category->id] ?? []) >= 10)
            <div class="cat-cta">
                <a href="{{ route('actualites') }}?category={{ $category->id }}" class="btn btn-secondary">
                    <i class="fa-solid fa-plus"></i> Voir tous les articles de {{ $category->nom }}
                </a>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</section>

@endsection

@push('scripts')
<script>
// ══════════ INDICATEUR GLISSANT DES TABS ══════════
function moveTabIndicator(tabEl) {
    const indicator = document.getElementById('tabIndicator');
    const wrap = document.getElementById('categoryTabs');
    if (!tabEl || !indicator) return;
    const tabRect = tabEl.getBoundingClientRect();
    const wrapRect = wrap.getBoundingClientRect();
    indicator.style.left  = (tabRect.left - wrapRect.left + wrap.scrollLeft) + 'px';
    indicator.style.width = tabRect.width + 'px';
}

function switchCategory(categoryId, tabEl) {
    document.querySelectorAll('.category-tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.category-content').forEach(content => content.classList.remove('active'));

    const selectedTab = document.querySelector(`[data-category="${categoryId}"]`);
    const selectedContent = document.getElementById(categoryId);

    if (selectedTab) selectedTab.classList.add('active');
    if (selectedContent) selectedContent.classList.add('active');

    moveTabIndicator(tabEl);
    revealCards(selectedContent);
}

// Placement initial de l'indicateur
window.addEventListener('load', () => {
    const activeTab = document.querySelector('.category-tab.active');
    moveTabIndicator(activeTab);
});
window.addEventListener('resize', () => {
    const activeTab = document.querySelector('.category-tab.active');
    moveTabIndicator(activeTab);
});

// ══════════ RÉVÉLATION PROGRESSIVE DES CARTES ══════════
function revealCards(container) {
    if (!container) return;
    const cards = container.querySelectorAll('.mod-card');
    cards.forEach((card, i) => {
        card.classList.remove('reveal');
        setTimeout(() => card.classList.add('reveal'), 60 * i);
    });
}

// ══════════ HERO SLIDER (rotation automatique) ══════════
(function () {
    const feature = document.getElementById('heroFeature');
    if (!feature) return;

    const slides    = Array.from(feature.querySelectorAll('.hero-slide'));
    const dots      = Array.from(feature.querySelectorAll('.hero-dot'));
    const listItems = document.querySelectorAll('[data-list-slide]');
    if (slides.length <= 1) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const DELAY = 6000;
    let current = 0;
    let timer = null;

    function goToSlide(index) {
        slides[current]?.classList.remove('active');
        dots[current]?.classList.remove('active');
        document.querySelector(`[data-list-slide="${current}"]`)?.classList.remove('is-active');

        current = (index + slides.length) % slides.length;

        slides[current]?.classList.add('active');
        dots[current]?.classList.add('active');
        document.querySelector(`[data-list-slide="${current}"]`)?.classList.add('is-active');
    }

    function next() { goToSlide(current + 1); }

    function startAutoplay() {
        if (prefersReducedMotion) return;
        stopAutoplay();
        timer = setInterval(next, DELAY);
    }
    function stopAutoplay() {
        if (timer) clearInterval(timer);
        timer = null;
    }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => { goToSlide(i); startAutoplay(); });
    });

    // survoler "aussi à la une" met en avant l'article correspondant dans le slider
    listItems.forEach(item => {
        const idx = parseInt(item.dataset.listSlide, 10);
        item.addEventListener('mouseenter', () => goToSlide(idx));
    });

    feature.addEventListener('mouseenter', stopAutoplay);
    feature.addEventListener('mouseleave', startAutoplay);

    startAutoplay();
})();


// Révéler les cartes visibles au chargement (première catégorie active)
document.addEventListener('DOMContentLoaded', () => {
    const firstActive = document.querySelector('.category-content.active');
    revealCards(firstActive);
});
</script>
@endpush