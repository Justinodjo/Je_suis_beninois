{{-- ═══════════════════════════════════════════════════════
     TOUTES LES ACTUALITÉS
     Route: GET /actualites → HomeController@actualites
     Variables: $articles (paginé), $categories, $activeCategory
═══════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', ($activeCategory ? $activeCategory->nom . ' — ' : '') . 'Actualités — Je Suis Béninois')

@push('styles')
<style>
.actu-hero {
    background: var(--vert);
    padding: 44px 0;
}
.actu-hero h1 {
    font-family: var(--font-titre);
    font-size: clamp(1.7rem, 3vw, 2.3rem);
    font-weight: 700;
    color: #fff;
}
.actu-hero p {
    color: rgba(255,255,255,.75);
    font-size: .9rem;
    margin-top: 8px;
}

.actu-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 32px 0 28px;
}
.actu-filter-pill {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: .82rem;
    font-weight: 600;
    border: 1.5px solid var(--border);
    color: var(--text-l);
    transition: all .2s;
}
.actu-filter-pill:hover { border-color: var(--vert); color: var(--vert); }
.actu-filter-pill.active { background: var(--vert); border-color: var(--vert); color: #fff; }

.actu-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 26px;
    margin-bottom: 40px;
}
.actu-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(15,61,20,.06);
    transition: transform .25s, box-shadow .25s;
}
.actu-card:hover { transform: translateY(-5px); box-shadow: 0 16px 32px rgba(15,61,20,.12); }
.actu-card-img { position: relative; height: 190px; overflow: hidden; }
.actu-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
.actu-card:hover .actu-card-img img { transform: scale(1.06); }
.actu-card-badge {
    position: absolute; top: 12px; left: 12px;
    background: rgba(27,94,32,.9);
    color: #fff;
    padding: 3px 11px;
    border-radius: 20px;
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
}
.actu-card-body { padding: 18px 20px; }
.actu-card-title {
    font-family: var(--font-titre);
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.35;
    margin-bottom: 8px;
    color: var(--text);
}
.actu-card-title a:hover { color: var(--vert); }
.actu-card-excerpt { font-size: .82rem; color: var(--text-l); line-height: 1.6; margin-bottom: 12px; }
.actu-card-meta { display: flex; gap: 14px; font-size: .74rem; color: var(--gris-t); }
.actu-card-meta i { margin-right: 4px; color: var(--vert); }

.actu-empty { text-align: center; padding: 60px 20px; color: var(--text-l); grid-column: 1/-1; }
.actu-empty i { font-size: 2.6rem; color: var(--border); margin-bottom: 14px; display: block; }

@media (max-width: 1024px) { .actu-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .actu-grid { grid-template-columns: 1fr; } }

.actu-hero-top {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

/* ── Mini-slider d'articles à la une (contextuel à la catégorie) ── */
.actu-slider {
    position: relative;
    margin-top: 28px;
    height: 340px;
    border-radius: 16px;
    overflow: hidden;
}
.actu-slide {
    position: absolute; inset: 0;
    display: flex;
    align-items: flex-end;
    opacity: 0;
    visibility: hidden;
    transition: opacity 1.1s ease;
    z-index: 1;
}
.actu-slide.active { opacity: 1; visibility: visible; z-index: 2; }
.actu-slide-bg {
    position: absolute; inset: 0;
    background-size: cover;
    background-position: center;
    transform: scale(1.04);
    transition: transform 7s cubic-bezier(.16,1,.3,1);
}
.actu-slide.active .actu-slide-bg { transform: scale(1); }
.actu-slide-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(6,20,8,.92) 0%, rgba(6,20,8,.5) 50%, rgba(6,20,8,.05) 85%);
}
.actu-slide-content {
    position: relative; z-index: 2;
    padding: 28px 32px;
    max-width: 620px;
}
.actu-slide-cat {
    display: inline-block;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--jaune);
    margin-bottom: 10px;
}
.actu-slide-title {
    font-family: var(--font-titre);
    font-size: clamp(1.2rem, 2.2vw, 1.6rem);
    font-weight: 700;
    line-height: 1.25;
    margin-bottom: 8px;
}
.actu-slide-title a { color: #fff; }
.actu-slide-title a:hover { color: var(--jaune); }
.actu-slide-excerpt {
    font-size: .86rem;
    color: rgba(255,255,255,.72);
    line-height: 1.55;
    max-width: 520px;
}

.actu-dots {
    position: absolute; bottom: 16px; right: 24px; z-index: 3;
    display: flex; gap: 8px;
}
.actu-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(255,255,255,.35); border: none; cursor: pointer; padding: 0;
    transition: background .25s, transform .25s;
}
.actu-dot.active { background: var(--jaune); transform: scale(1.25); }

@media (prefers-reduced-motion: reduce) {
    .actu-slide, .actu-slide-bg { transition: none !important; }
}
@media (max-width: 640px) {
    .actu-slider { height: 260px; }
    .actu-slide-content { padding: 20px 22px; }
}


</style>
@endpush

@section('content')

@php
    $featured = collect($articles->items())->take(4)->values();
@endphp

<section class="actu-hero">
    <div class="container">
        <div class="actu-hero-top">
            <div>
                <h1>{{ $activeCategory ? $activeCategory->nom : 'Toutes les actualités' }}</h1>
                <p>{{ $articles->total() }} article{{ $articles->total() !== 1 ? 's' : '' }} publié{{ $articles->total() !== 1 ? 's' : '' }}</p>
            </div>
        </div>

        @if($featured->count() > 1)
        <div class="actu-slider" id="actuSlider">
            @foreach($featured as $i => $article)
            <div class="actu-slide {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}">
                <div class="actu-slide-bg" style="background-image:url('{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1200&q=80' }}');"></div>
                <div class="actu-slide-overlay"></div>
                <div class="actu-slide-content">
                    <span class="actu-slide-cat">{{ $article->categories->first()?->nom ?? 'Actualités' }}</span>
                    <h2 class="actu-slide-title">
                        <a href="{{ route('culture.article', $article->slug) }}">{{ Str::limit($article->titre, 80) }}</a>
                    </h2>
                    <p class="actu-slide-excerpt">
                        {{ Str::limit($article->extrait ?? strip_tags($article->contenu), 140) }}
                    </p>
                </div>
            </div>
            @endforeach

            <div class="actu-dots">
                @foreach($featured as $i => $article)
                <button type="button" class="actu-dot {{ $i === 0 ? 'active' : '' }}" data-slide-btn="{{ $i }}" aria-label="Article {{ $i + 1 }}"></button>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<div class="container">

    {{-- Filtres catégories --}}
    <div class="actu-filters">
        <a href="{{ route('actualites') }}" class="actu-filter-pill {{ !$activeCategory ? 'active' : '' }}">
            Toutes
        </a>
        @foreach($categories as $category)
        <a href="{{ route('actualites') }}?category={{ $category->id }}"
           class="actu-filter-pill {{ $activeCategory?->id === $category->id ? 'active' : '' }}">
            {{ $category->nom }} <span style="opacity:.7;">({{ $category->articles_count ?? 0 }})</span>
        </a>
        @endforeach
    </div>

    {{-- Grille articles --}}
    <div class="actu-grid">
        @forelse($articles as $article)
        <article class="actu-card">
            <div class="actu-card-img">
                <a href="{{ route('culture.article', $article->slug) }}">
                    <img src="{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=600&q=80' }}"
                         alt="{{ $article->titre }}">
                </a>
                @if($article->categories->first())
                <span class="actu-card-badge">{{ $article->categories->first()->nom }}</span>
                @endif
            </div>
            <div class="actu-card-body">
                <h3 class="actu-card-title">
                    <a href="{{ route('culture.article', $article->slug) }}">{{ Str::limit($article->titre, 65) }}</a>
                </h3>
                <p class="actu-card-excerpt">
                    {{ Str::limit($article->extrait ?? strip_tags($article->contenu), 100) }}
                </p>
                <div class="actu-card-meta">
                    <span><i class="fa-regular fa-calendar"></i>{{ $article->created_at?->diffForHumans() }}</span>
                    <span><i class="fa-regular fa-eye"></i>{{ number_format($article->nb_vues) }}</span>
                    <span><i class="fa-solid fa-heart"></i>{{ $article->nb_likes }}</span>
                </div>
            </div>
        </article>
        @empty
        <div class="actu-empty">
            <i class="fa-solid fa-inbox"></i>
            <p>Aucun article {{ $activeCategory ? 'dans cette catégorie' : 'publié' }} pour le moment.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div style="margin-bottom:48px;">
        {{ $articles->links() }}
    </div>

</div>

@endsection

@push('scripts')
<script>
(function () {
    const slider = document.getElementById('actuSlider');
    if (!slider) return;

    const slides = Array.from(slider.querySelectorAll('.actu-slide'));
    const dots   = Array.from(slider.querySelectorAll('.actu-dot'));
    if (slides.length <= 1) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const DELAY = 6000;
    let current = 0;
    let timer = null;

    function goToSlide(index) {
        slides[current]?.classList.remove('active');
        dots[current]?.classList.remove('active');
        current = (index + slides.length) % slides.length;
        slides[current]?.classList.add('active');
        dots[current]?.classList.add('active');
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

    dots.forEach((dot, i) => dot.addEventListener('click', () => { goToSlide(i); startAutoplay(); }));
    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);

    startAutoplay();
})();
</script>
@endpush